@extends('layout.app')

@section('title', 'Products - ' . config('app.name'))

@section('content')

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <h1 class="h2 mb-4">
                    <i class="fas fa-box me-2"></i>Products
                </h1>
                <p class="text-muted">Browse our amazing collection of products</p>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    This is a placeholder products page. In a real application, this would display products from your API.
                    <br>
                    <strong>API Endpoint:</strong> <code>GET /api/v1/products</code>
                </div>
                
                <div class="text-center py-5">
                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                    <h4>Products Coming Soon</h4>
                    <p class="text-muted">We're working on bringing you amazing products!</p>
                    <a href="{{ url('/cart') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-cart me-2"></i>View Cart
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection

