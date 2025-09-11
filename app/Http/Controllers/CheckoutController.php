<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class CheckoutController extends Controller
{
    /**
     * Display the checkout page
     */
    public function index()
    {
        return view('checkout');
    }

    /**
     * Create order and generate ECPay payment parameters (API version)
     */
    public function createECPayOrder(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'shipping' => 'required|array',
                'shipping.fullName' => 'required|string|max:255',
                'shipping.email' => 'required|email|max:255',
                'shipping.phone' => 'required|string|max:20',
                'shipping.address' => 'required|string|max:500',
                'shipping.city' => 'required|string|max:100',
                'shipping.zipCode' => 'required|string|max:10',
                'items' => 'required|array|min:1',
            ]);

            DB::beginTransaction();

            // Get authenticated member
            $member = Auth::guard('member')->user();
            
            if (!$member) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }
            
            // Get cart data - find cart by user_id
            $cart = Cart::where('user_id', $member->id)->first();
            
            if (!$cart || $cart->items()->count() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty'
                ], 400);
            }

            // Calculate total amount
            $totalAmount = 0;
            $itemNames = [];
            
            foreach ($cart->items as $cartItem) {
                $itemTotal = $cartItem->price * $cartItem->quantity;
                $totalAmount += $itemTotal;
                $itemNames[] = $cartItem->product->name;
            }

            // Generate order number: O + YYYYMMDD + sequence number
            $orderNumber = $this->generateOrderNumber();

            // Create order
            $order = Order::create([
                'member_id' => $member->id,
                'order_number' => $orderNumber,
                'status' => 'pending',
                'total_amount' => $totalAmount,
                'shipping_name' => $request->shipping['fullName'],
                'shipping_email' => $request->shipping['email'],
                'shipping_phone' => $request->shipping['phone'],
                'shipping_address' => $request->shipping['address'],
                'shipping_city' => $request->shipping['city'],
                'shipping_zip_code' => $request->shipping['zipCode'],
                'payment_method' => 'ecpay',
                'payment_status' => 'pending',
            ]);

            // Create order items
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                ]);
            }

            // Generate ECPay parameters
            $ecpayParams = $this->generateECPayParams($order, $itemNames, $totalAmount);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'ecpay_params' => $ecpayParams
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create order and generate ECPay payment parameters (Session version)
     */
    public function createECPayOrderSession(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'shipping' => 'required|array',
                'shipping.fullName' => 'required|string|max:255',
                'shipping.email' => 'required|email|max:255',
                'shipping.phone' => 'required|string|max:20',
                'shipping.address' => 'required|string|max:500',
                'shipping.city' => 'required|string|max:100',
                'shipping.zipCode' => 'required|string|max:10',
                'items' => 'required|array|min:1',
            ]);

            DB::beginTransaction();

            // Get authenticated member from session
            $member = Auth::guard('member')->user();
            
            if (!$member) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }
            
            // Get cart data - find cart by user_id
            $cart = Cart::where('user_id', $member->id)->first();
            
            if (!$cart || $cart->items()->count() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty'
                ], 400);
            }

            // Calculate total amount
            $totalAmount = 0;
            $itemNames = [];
            
            foreach ($cart->items as $cartItem) {
                $itemTotal = $cartItem->price * $cartItem->quantity;
                $totalAmount += $itemTotal;
                $itemNames[] = $cartItem->product->name;
            }

            // Generate order number: O + YYYYMMDD + sequence number
            $orderNumber = $this->generateOrderNumber();

            // Create order
            $order = Order::create([
                'member_id' => $member->id,
                'order_number' => $orderNumber,
                'status' => 'pending',
                'total_amount' => $totalAmount,
                'shipping_name' => $request->shipping['fullName'],
                'shipping_email' => $request->shipping['email'],
                'shipping_phone' => $request->shipping['phone'],
                'shipping_address' => $request->shipping['address'],
                'shipping_city' => $request->shipping['city'],
                'shipping_zip_code' => $request->shipping['zipCode'],
                'payment_method' => 'ecpay',
                'payment_status' => 'pending',
            ]);

            // Create order items
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                ]);
            }

            // Generate ECPay parameters
            $ecpayParams = $this->generateECPayParams($order, $itemNames, $totalAmount);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'ecpay_params' => $ecpayParams
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate order number: O + YYYYMMDD + sequence number
     */
    private function generateOrderNumber(): string
    {
        $datePrefix = 'O' . date('Ymd');
        
        // Get the last order number for today
        $lastOrder = Order::where('order_number', 'like', $datePrefix . '%')
            ->orderBy('order_number', 'desc')
            ->first();
        
        if ($lastOrder) {
            // Extract sequence number from last order
            $lastSequence = (int) substr($lastOrder->order_number, -4);
            $sequence = $lastSequence + 1;
        } else {
            // First order of the day
            $sequence = 1;
        }
        
        // Format sequence number with leading zeros (4 digits)
        return $datePrefix . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate ECPay payment parameters
     */
    private function generateECPayParams(Order $order, array $itemNames, int $totalAmount): array
    {
        // ECPay test environment configuration
        $merchantId = '3002607'; // Test merchant ID
        $hashKey = 'pwFHCqoQDZGmho4w'; // Test hash key
        $hashIv = 'EkRm7iFT261dpevs'; // Test hash IV
        
        $merchantTradeNo = $order->order_number;
        $merchantTradeDate = $order->created_at->format('Y/m/d H:i:s');
        $paymentType = 'aio';
        $tradeDesc = 'Shopping Store Order';
        $itemName = implode('#', $itemNames);
        $returnUrl = route('checkout.ecpay.return');
        $choosePayment = 'Credit';
        $encryptType = 1;
        $clientBackUrl = route('checkout.success', $order->id);

        // Prepare parameters for CheckMacValue
        $params = [
            'MerchantID' => $merchantId,
            'MerchantTradeNo' => $merchantTradeNo,
            'MerchantTradeDate' => $merchantTradeDate,
            'PaymentType' => $paymentType,
            'TotalAmount' => $totalAmount,
            'TradeDesc' => $tradeDesc,
            'ItemName' => $itemName,
            'ReturnURL' => $returnUrl,
            'ChoosePayment' => $choosePayment,
            'EncryptType' => $encryptType,
            'ClientBackURL' => $clientBackUrl,
        ];

        // Generate CheckMacValue
        $checkMacValue = $this->generateCheckMacValue($params, $hashKey, $hashIv);
        $params['CheckMacValue'] = $checkMacValue;

        return $params;
    }

    /**
     * Generate ECPay CheckMacValue
     */
    private function generateCheckMacValue(array $params, string $hashKey, string $hashIv): string
    {
        // Sort parameters by key
        ksort($params);
        
        // Create query string
        $queryString = http_build_query($params);
        
        // Add hash key and IV
        $queryString = 'HashKey=' . $hashKey . '&' . $queryString . '&HashIV=' . $hashIv;
        
        // URL encode
        $queryString = urlencode($queryString);
        
        // Convert to lowercase
        $queryString = strtolower($queryString);
        
        // Generate SHA256 hash
        $checkMacValue = hash('sha256', $queryString);
        
        return strtoupper($checkMacValue);
    }

    /**
     * Handle ECPay payment return
     */
    public function handleECPayReturn(Request $request): string
    {
        // Log the return data for debugging
        Log::info('ECPay Return Data:', $request->all());
        
        // Verify the payment result
        $isValid = $this->verifyECPayReturn($request);
        
        if ($isValid) {
            // Update order status
            $orderNumber = $request->input('MerchantTradeNo');
            $order = Order::where('order_number', $orderNumber)->first();
            
            if ($order) {
                $order->update([
                    'payment_status' => $request->input('RtnCode') === '1' ? 'paid' : 'failed',
                    'ecpay_trade_no' => $request->input('TradeNo'),
                    'ecpay_rtn_code' => $request->input('RtnCode'),
                    'ecpay_rtn_msg' => $request->input('RtnMsg'),
                ]);
            }
        }
        
        // Return response to ECPay
        return '1|OK';
    }

    /**
     * Verify ECPay return data
     */
    private function verifyECPayReturn(Request $request): bool
    {
        $hashKey = 'pwFHCqoQDZGmho4w'; // Test hash key
        $hashIv = 'EkRm7iFT261dpevs'; // Test hash IV
        
        $receivedCheckMacValue = $request->input('CheckMacValue');
        $params = $request->except(['CheckMacValue']);
        
        $calculatedCheckMacValue = $this->generateCheckMacValue($params, $hashKey, $hashIv);
        
        return $receivedCheckMacValue === $calculatedCheckMacValue;
    }

    /**
     * Show checkout success page
     */
    public function success(Order $order)
    {
        return view('checkout-success', compact('order'));
    }
}
