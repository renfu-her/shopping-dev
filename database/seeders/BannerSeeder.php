<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

final class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Welcome to Our Store',
                'description' => 'Discover amazing products at unbeatable prices',
                'image' => '/example/assets/images/slider-image/sample-1.jpg',
                'link' => '/products',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'New Arrivals',
                'description' => 'Check out our latest collection of products',
                'image' => '/example/assets/images/slider-image/sample-2.jpg',
                'link' => '/products?new=true',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Special Offers',
                'description' => 'Limited time offers on selected items',
                'image' => '/example/assets/images/slider-image/sample-3.jpg',
                'link' => '/products?sale=true',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
}
