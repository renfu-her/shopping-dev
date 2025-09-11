@extends('layout.app')

@section('title', 'Order Success - ' . config('app.name'))

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fas fa-check-circle fa-5x text-success"></i>
                        </div>
                        
                        <h1 class="display-4 text-success mb-3">Order Successful!</h1>
                        <p class="lead text-muted mb-4">
                            Thank you for your purchase. Your order has been received and is being processed.
                        </p>
                        
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Order Details</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                                        <p><strong>Order Date:</strong> {{ $order->created_at->format('M j, Y g:i A') }}</p>
                                        <p><strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Payment Status:</strong> 
                                            <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                                {{ ucfirst($order->payment_status) }}
                                            </span>
                                        </p>
                                        <p><strong>Order Status:</strong> 
                                            <span class="badge bg-info">{{ ucfirst($order->status) }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Shipping Address</h5>
                                <p class="mb-0">
                                    {{ $order->shipping_name }}<br>
                                    {{ $order->shipping_address }}<br>
                                    {{ $order->shipping_city }} {{ $order->shipping_zip_code }}<br>
                                    {{ $order->shipping_phone }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <a href="{{ route('products') }}" class="btn btn-primary btn-lg me-md-2">
                                <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-home me-2"></i>Back to Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
