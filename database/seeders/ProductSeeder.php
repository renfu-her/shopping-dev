<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get categories
        $smartphones = Category::where('slug', 'smartphones')->first();
        $laptops = Category::where('slug', 'laptops')->first();
        $audioVideo = Category::where('slug', 'audio-video')->first();
        $gaming = Category::where('slug', 'gaming')->first();
        $mensClothing = Category::where('slug', 'mens-clothing')->first();
        $womensClothing = Category::where('slug', 'womens-clothing')->first();
        $furniture = Category::where('slug', 'furniture')->first();
        $kitchen = Category::where('slug', 'kitchen-dining')->first();
        $fitness = Category::where('slug', 'fitness-equipment')->first();
        $books = Category::where('slug', 'books')->first();

        // Create products
        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'slug' => 'iphone-15-pro',
                'sku' => 'IPH15P-256-BLK',
                'short_description' => 'Latest iPhone with advanced camera system',
                'description' => 'The iPhone 15 Pro features a titanium design, A17 Pro chip, and advanced camera system with 48MP main camera.',
                'price' => 999.00,
                'sale_price' => 899.00,
                'stock_quantity' => 50,
                'is_featured' => true,
                'category' => $smartphones,
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'slug' => 'samsung-galaxy-s24-ultra',
                'sku' => 'SGS24U-512-TIT',
                'short_description' => 'Premium Android smartphone with S Pen',
                'description' => 'The Samsung Galaxy S24 Ultra features a large 6.8-inch display, S Pen stylus, and powerful camera system.',
                'price' => 1199.00,
                'stock_quantity' => 30,
                'is_featured' => true,
                'category' => $smartphones,
            ],
            [
                'name' => 'MacBook Pro 16-inch',
                'slug' => 'macbook-pro-16-inch',
                'sku' => 'MBP16-M3-1TB',
                'short_description' => 'Powerful laptop for professionals',
                'description' => 'The MacBook Pro 16-inch with M3 chip delivers exceptional performance for creative professionals.',
                'price' => 2499.00,
                'stock_quantity' => 20,
                'is_featured' => true,
                'category' => $laptops,
            ],
            [
                'name' => 'AirPods Pro (2nd generation)',
                'slug' => 'airpods-pro-2nd-gen',
                'sku' => 'APP2-WHT',
                'short_description' => 'Wireless earbuds with noise cancellation',
                'description' => 'AirPods Pro (2nd generation) feature active noise cancellation, spatial audio, and up to 6 hours of listening time.',
                'price' => 249.00,
                'stock_quantity' => 100,
                'is_featured' => true,
                'category' => $audioVideo,
            ],
            [
                'name' => 'PlayStation 5 Console',
                'slug' => 'playstation-5-console',
                'sku' => 'PS5-STD-1TB',
                'short_description' => 'Next-generation gaming console',
                'description' => 'The PlayStation 5 delivers lightning-fast loading, haptic feedback, and 3D audio.',
                'price' => 499.00,
                'stock_quantity' => 15,
                'is_featured' => true,
                'category' => $gaming,
            ],
            [
                'name' => 'Classic Cotton T-Shirt',
                'slug' => 'classic-cotton-t-shirt',
                'sku' => 'CCT-M-BLK',
                'short_description' => 'Comfortable cotton t-shirt for everyday wear',
                'description' => 'Made from 100% premium cotton, this classic t-shirt offers comfort and style.',
                'price' => 29.99,
                'sale_price' => 24.99,
                'stock_quantity' => 200,
                'is_featured' => false,
                'category' => $mensClothing,
            ],
            [
                'name' => 'Elegant Summer Dress',
                'slug' => 'elegant-summer-dress',
                'sku' => 'ESD-S-BLU',
                'short_description' => 'Beautiful summer dress for special occasions',
                'description' => 'This elegant summer dress features a flattering silhouette and beautiful floral pattern.',
                'price' => 89.99,
                'stock_quantity' => 75,
                'is_featured' => true,
                'category' => $womensClothing,
            ],
            [
                'name' => 'Ergonomic Office Chair',
                'slug' => 'ergonomic-office-chair',
                'sku' => 'EOC-BLK',
                'short_description' => 'Comfortable ergonomic chair for office work',
                'description' => 'This ergonomic office chair provides excellent lumbar support and adjustable features.',
                'price' => 299.99,
                'sale_price' => 249.99,
                'stock_quantity' => 40,
                'is_featured' => false,
                'category' => $furniture,
            ],
            [
                'name' => 'Premium Coffee Maker',
                'slug' => 'premium-coffee-maker',
                'sku' => 'PCM-SIL',
                'short_description' => 'High-quality coffee maker for perfect brew',
                'description' => 'This premium coffee maker features programmable settings, thermal carafe, and multiple brewing options.',
                'price' => 199.99,
                'stock_quantity' => 60,
                'is_featured' => true,
                'category' => $kitchen,
            ],
            [
                'name' => 'Premium Yoga Mat',
                'slug' => 'premium-yoga-mat',
                'sku' => 'PYM-PUR',
                'short_description' => 'Non-slip yoga mat for all fitness activities',
                'description' => 'This premium yoga mat provides excellent grip and cushioning for yoga, pilates, and other fitness activities.',
                'price' => 49.99,
                'stock_quantity' => 150,
                'is_featured' => false,
                'category' => $fitness,
            ],
            [
                'name' => 'Laravel: The Complete Guide',
                'slug' => 'laravel-complete-guide',
                'sku' => 'LCG-2024',
                'short_description' => 'Comprehensive guide to Laravel framework',
                'description' => 'This comprehensive guide covers everything you need to know about Laravel framework, from basics to advanced topics.',
                'price' => 59.99,
                'sale_price' => 49.99,
                'stock_quantity' => 80,
                'is_featured' => true,
                'category' => $books,
            ],
        ];

        foreach ($products as $productData) {
            $category = $productData['category'];
            unset($productData['category']);

            $product = Product::firstOrCreate(
                ['sku' => $productData['sku']],
                array_merge($productData, [
                    'min_stock_level' => 5,
                    'manage_stock' => true,
                    'allow_backorder' => false,
                    'weight' => 1.0,
                    'is_active' => true,
                    'meta_title' => $productData['name'],
                    'meta_description' => $productData['short_description'],
                ])
            );

            if ($category) {
                $product->categories()->syncWithoutDetaching([$category->id]);
            }
        }
    }
}