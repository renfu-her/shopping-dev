<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Get user orders
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $orders = $request->user()->orders()
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return OrderResource::collection($orders);
    }

    /**
     * Get single order
     */
    public function show(Request $request, Order $order): JsonResponse
    {
        // Verify order belongs to user
        if ($order->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        }

        $order->load(['items.product']);

        return response()->json([
            'success' => true,
            'data' => new OrderResource($order),
        ]);
    }

    /**
     * Create new order
     */
    public function store(CreateOrderRequest $request): JsonResponse
    {
        $user = $request->user();
        $cart = $user->cart;

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty',
            ], 400);
        }

        DB::transaction(function () use ($request, $user, $cart) {
            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $this->generateOrderNumber(),
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'shipping_address' => $request->shipping_address,
                'billing_address' => $request->billing_address,
                'subtotal' => $cart->items->sum(function ($item) {
                    return $item->quantity * $item->price;
                }),
                'tax_amount' => $request->tax_amount ?? 0,
                'shipping_amount' => $request->shipping_amount ?? 0,
                'discount_amount' => $this->calculateDiscount($cart),
                'total_amount' => $this->calculateTotal($cart, $request),
                'coupon_data' => $cart->coupon_data,
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                ]);

                // Update product stock
                if ($cartItem->product->manage_stock) {
                    $cartItem->product->decrement('stock_quantity', $cartItem->quantity);
                }
            }

            // Update coupon usage if applied
            if ($cart->coupon_id) {
                \App\Models\Coupon::where('id', $cart->coupon_id)->increment('used_count');
                
                // Record user coupon usage
                \App\Models\UserCoupon::create([
                    'user_id' => $user->id,
                    'coupon_id' => $cart->coupon_id,
                    'order_id' => $order->id,
                    'used_at' => now(),
                ]);
            }

            // Clear cart
            $cart->items()->delete();
            $cart->update([
                'coupon_id' => null,
                'coupon_data' => null,
            ]);

            // Send order confirmation email
            // TODO: Implement email sending
        });

        $order = Order::with(['items.product'])
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully',
            'data' => new OrderResource($order),
        ], 201);
    }

    /**
     * Cancel order
     */
    public function cancel(Request $request, Order $order): JsonResponse
    {
        // Verify order belongs to user
        if ($order->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        }

        // Check if order can be cancelled
        if (!in_array($order->status, ['pending', 'processing'])) {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be cancelled',
            ], 400);
        }

        DB::transaction(function () use ($order) {
            // Update order status
            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            // Restore product stock
            foreach ($order->items as $item) {
                if ($item->product->manage_stock) {
                    $item->product->increment('stock_quantity', $item->quantity);
                }
            }

            // Handle coupon refund if applicable
            if ($order->coupon_data) {
                \App\Models\Coupon::where('id', $order->coupon_id)->decrement('used_count');
                
                // Remove user coupon usage record
                \App\Models\UserCoupon::where('order_id', $order->id)->delete();
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully',
            'data' => new OrderResource($order->fresh()),
        ]);
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-' . strtoupper(uniqid());
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Calculate discount amount
     */
    private function calculateDiscount($cart): float
    {
        if (!$cart->coupon_id || !$cart->coupon_data) {
            return 0;
        }

        $subtotal = $cart->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $couponData = $cart->coupon_data;
        if ($couponData['type'] === 'percentage') {
            return ($subtotal * $couponData['value']) / 100;
        } elseif ($couponData['type'] === 'fixed') {
            return $couponData['value'];
        }

        return 0;
    }

    /**
     * Calculate total amount
     */
    private function calculateTotal($cart, $request): float
    {
        $subtotal = $cart->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $taxAmount = $request->tax_amount ?? 0;
        $shippingAmount = $request->shipping_amount ?? 0;
        $discountAmount = $this->calculateDiscount($cart);

        return $subtotal + $taxAmount + $shippingAmount - $discountAmount;
    }
}
