<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * Get cart contents
     */
    public function index(Request $request): JsonResponse
    {
        $cart = $this->getOrCreateCart($request);
        $cart->load(['items.product.categories']);

        return response()->json([
            'success' => true,
            'data' => new CartResource($cart),
        ]);
    }

    /**
     * Get cart item count
     */
    public function count(Request $request): JsonResponse
    {
        $cart = $this->getOrCreateCart($request);
        $count = $cart->items()->sum('quantity');

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }

    /**
     * Add product to cart
     */
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if product is active and in stock
        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available',
            ], 400);
        }

        if ($product->manage_stock && $product->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock. Available: ' . $product->stock_quantity,
            ], 400);
        }

        $cart = $this->getOrCreateCart($request);

        DB::transaction(function () use ($cart, $product, $request) {
            // Check if item already exists in cart
            $existingItem = $cart->items()->where('product_id', $product->id)->first();

            if ($existingItem) {
                $newQuantity = $existingItem->quantity + $request->quantity;
                
                // Check stock again for updated quantity
                if ($product->manage_stock && $product->stock_quantity < $newQuantity) {
                    throw new \Exception('Insufficient stock for updated quantity');
                }

                $existingItem->update([
                    'quantity' => $newQuantity,
                    'price' => $product->sale_price ?? $product->price,
                ]);
            } else {
                $cart->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                    'price' => $product->sale_price ?? $product->price,
                ]);
            }
        });

        $cart->load(['items.product.categories']);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully',
            'data' => new CartResource($cart),
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, CartItem $cartItem): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:0|max:99',
        ]);

        $cart = $this->getOrCreateCart($request);

        // Verify cart item belongs to user's cart
        if ($cartItem->cart_id !== $cart->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found',
            ], 404);
        }

        if ($request->quantity === 0) {
            $cartItem->delete();
        } else {
            $product = $cartItem->product;

            // Check stock availability
            if ($product->manage_stock && $product->stock_quantity < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock. Available: ' . $product->stock_quantity,
                ], 400);
            }

            $cartItem->update([
                'quantity' => $request->quantity,
                'price' => $product->sale_price ?? $product->price,
            ]);
        }

        $cart->load(['items.product.categories']);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
            'data' => new CartResource($cart),
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove(CartItem $cartItem): JsonResponse
    {
        $cart = $this->getOrCreateCart(request());

        // Verify cart item belongs to user's cart
        if ($cartItem->cart_id !== $cart->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found',
            ], 404);
        }

        $cartItem->delete();
        $cart->load(['items.product.categories']);

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully',
            'data' => new CartResource($cart),
        ]);
    }

    /**
     * Clear cart
     */
    public function clear(Request $request): JsonResponse
    {
        $cart = $this->getOrCreateCart($request);
        $cart->items()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully',
            'data' => new CartResource($cart),
        ]);
    }

    /**
     * Apply coupon to cart
     */
    public function applyCoupon(Request $request): JsonResponse
    {
        $request->validate([
            'coupon_code' => 'required|string|max:255',
        ]);

        $cart = $this->getOrCreateCart($request);
        $coupon = \App\Models\Coupon::where('code', $request->coupon_code)
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code',
            ], 400);
        }

        // Validate coupon
        $validation = $this->validateCoupon($coupon, $cart);
        if (!$validation['valid']) {
            return response()->json([
                'success' => false,
                'message' => $validation['message'],
            ], 400);
        }

        // Apply coupon to cart
        $cart->update([
            'coupon_id' => $coupon->id,
            'coupon_data' => [
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => $coupon->value,
            ],
        ]);

        $cart->load(['items.product.categories']);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully',
            'data' => new CartResource($cart),
        ]);
    }

    /**
     * Remove coupon from cart
     */
    public function removeCoupon(Request $request): JsonResponse
    {
        $cart = $this->getOrCreateCart($request);
        $cart->update([
            'coupon_id' => null,
            'coupon_data' => null,
        ]);

        $cart->load(['items.product.categories']);

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed successfully',
            'data' => new CartResource($cart),
        ]);
    }

    /**
     * Get or create cart for user/guest
     */
    private function getOrCreateCart(Request $request): Cart
    {
        if (Auth::check()) {
            // Authenticated user
            $cart = Cart::where('user_id', Auth::id())
                ->whereNull('session_id')
                ->first();

            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => Auth::id(),
                    'session_id' => null,
                ]);
            }
        } else {
            // Guest user
            $sessionId = $request->session()->getId();
            $cart = Cart::where('session_id', $sessionId)
                ->whereNull('user_id')
                ->first();

            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => null,
                    'session_id' => $sessionId,
                ]);
            }
        }

        return $cart;
    }

    /**
     * Validate coupon for cart
     */
    private function validateCoupon($coupon, $cart): array
    {
        // Check if coupon is valid
        if ($coupon->valid_from && $coupon->valid_from > now()) {
            return ['valid' => false, 'message' => 'Coupon is not yet valid'];
        }

        if ($coupon->valid_until && $coupon->valid_until < now()) {
            return ['valid' => false, 'message' => 'Coupon has expired'];
        }

        // Check usage limits
        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return ['valid' => false, 'message' => 'Coupon usage limit reached'];
        }

        // Check minimum amount
        $cartTotal = $cart->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        if ($coupon->minimum_amount && $cartTotal < $coupon->minimum_amount) {
            return ['valid' => false, 'message' => 'Minimum order amount not met'];
        }

        return ['valid' => true, 'message' => 'Coupon is valid'];
    }
}
