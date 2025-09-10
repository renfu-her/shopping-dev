@extends('layout.app')

@section('title', 'About Us - ' . config('app.name'))


@section('content')

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">About {{ config('app.name') }}</h1>
                    <p class="lead">We are passionate about providing you with the best shopping experience, quality products, and exceptional customer service.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <h2 class="mb-4">Our Story</h2>
                    <p class="lead">Founded in 2020, {{ config('app.name') }} started as a small online store with a big dream: to make quality products accessible to everyone.</p>
                    <p>What began as a passion project has grown into a trusted e-commerce platform serving thousands of customers worldwide. We believe that shopping should be convenient, enjoyable, and rewarding.</p>
                    <p>Our commitment to quality, customer satisfaction, and innovation has made us a leader in the online retail space. We continuously strive to improve our services and expand our product range to meet the evolving needs of our customers.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-number">10K+</div>
                        <h5>Happy Customers</h5>
                        <p class="text-muted">Satisfied customers worldwide</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-number">5K+</div>
                        <h5>Products</h5>
                        <p class="text-muted">Quality products in our catalog</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-number">50+</div>
                        <h5>Countries</h5>
                        <p class="text-muted">Serving customers globally</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <h5>Support</h5>
                        <p class="text-muted">Round-the-clock customer service</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2>Our Values</h2>
                <p class="lead">The principles that guide everything we do</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="feature-icon about">
                            <i class="fas fa-heart fa-2x"></i>
                        </div>
                        <h4>Customer First</h4>
                        <p>Our customers are at the heart of everything we do. We listen, learn, and continuously improve to exceed your expectations.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="feature-icon about">
                            <i class="fas fa-gem fa-2x"></i>
                        </div>
                        <h4>Quality</h4>
                        <p>We carefully curate our products to ensure they meet the highest standards of quality and durability.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="feature-icon about">
                            <i class="fas fa-rocket fa-2x"></i>
                        </div>
                        <h4>Innovation</h4>
                        <p>We embrace new technologies and innovative solutions to enhance your shopping experience.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="feature-icon about">
                            <i class="fas fa-handshake fa-2x"></i>
                        </div>
                        <h4>Trust</h4>
                        <p>We build lasting relationships with our customers through transparency, reliability, and honest communication.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="feature-icon about">
                            <i class="fas fa-leaf fa-2x"></i>
                        </div>
                        <h4>Sustainability</h4>
                        <p>We are committed to sustainable practices and environmentally friendly solutions in our operations.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="feature-icon about">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <h4>Community</h4>
                        <p>We believe in building a strong community of customers, partners, and team members who share our values.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Mission Section -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <!-- Image removed -->
                </div>
                <div class="col-lg-6">
                    <h2 class="mb-4">Our Mission</h2>
                    <p class="lead">To democratize access to quality products while providing an exceptional shopping experience that exceeds customer expectations.</p>
                    <p>We are committed to:</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Offering the best prices without compromising on quality</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Providing fast, reliable shipping worldwide</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Ensuring 100% customer satisfaction</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Supporting sustainable and ethical business practices</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Continuously innovating to improve our services</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

@endsection
