<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Main categories
            ['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Electronic devices and gadgets', 'sort_order' => 1],
            ['name' => 'Clothing', 'slug' => 'clothing', 'description' => 'Fashion and apparel', 'sort_order' => 2],
            ['name' => 'Home & Garden', 'slug' => 'home-garden', 'description' => 'Home improvement and garden supplies', 'sort_order' => 3],
            ['name' => 'Sports & Outdoors', 'slug' => 'sports-outdoors', 'description' => 'Sports equipment and outdoor gear', 'sort_order' => 4],
            ['name' => 'Books & Media', 'slug' => 'books-media', 'description' => 'Books, movies, and music', 'sort_order' => 5],
            
            // Electronics subcategories
            ['name' => 'Smartphones', 'slug' => 'smartphones', 'description' => 'Mobile phones and accessories', 'parent_slug' => 'electronics', 'sort_order' => 1],
            ['name' => 'Laptops', 'slug' => 'laptops', 'description' => 'Laptop computers and accessories', 'parent_slug' => 'electronics', 'sort_order' => 2],
            ['name' => 'Audio & Video', 'slug' => 'audio-video', 'description' => 'Audio and video equipment', 'parent_slug' => 'electronics', 'sort_order' => 3],
            ['name' => 'Gaming', 'slug' => 'gaming', 'description' => 'Gaming consoles and accessories', 'parent_slug' => 'electronics', 'sort_order' => 4],
            
            // Clothing subcategories
            ['name' => 'Men\'s Clothing', 'slug' => 'mens-clothing', 'description' => 'Men\'s fashion and apparel', 'parent_slug' => 'clothing', 'sort_order' => 1],
            ['name' => 'Women\'s Clothing', 'slug' => 'womens-clothing', 'description' => 'Women\'s fashion and apparel', 'parent_slug' => 'clothing', 'sort_order' => 2],
            ['name' => 'Kids\' Clothing', 'slug' => 'kids-clothing', 'description' => 'Children\'s fashion and apparel', 'parent_slug' => 'clothing', 'sort_order' => 3],
            ['name' => 'Shoes', 'slug' => 'shoes', 'description' => 'Footwear for all ages', 'parent_slug' => 'clothing', 'sort_order' => 4],
            
            // Home & Garden subcategories
            ['name' => 'Furniture', 'slug' => 'furniture', 'description' => 'Home and office furniture', 'parent_slug' => 'home-garden', 'sort_order' => 1],
            ['name' => 'Kitchen & Dining', 'slug' => 'kitchen-dining', 'description' => 'Kitchen appliances and dining accessories', 'parent_slug' => 'home-garden', 'sort_order' => 2],
            ['name' => 'Garden Tools', 'slug' => 'garden-tools', 'description' => 'Gardening tools and equipment', 'parent_slug' => 'home-garden', 'sort_order' => 3],
            
            // Sports & Outdoors subcategories
            ['name' => 'Fitness Equipment', 'slug' => 'fitness-equipment', 'description' => 'Home and gym fitness equipment', 'parent_slug' => 'sports-outdoors', 'sort_order' => 1],
            ['name' => 'Outdoor Gear', 'slug' => 'outdoor-gear', 'description' => 'Camping and outdoor adventure gear', 'parent_slug' => 'sports-outdoors', 'sort_order' => 2],
            ['name' => 'Team Sports', 'slug' => 'team-sports', 'description' => 'Equipment for team sports', 'parent_slug' => 'sports-outdoors', 'sort_order' => 3],
            
            // Books & Media subcategories
            ['name' => 'Books', 'slug' => 'books', 'description' => 'Fiction and non-fiction books', 'parent_slug' => 'books-media', 'sort_order' => 1],
            ['name' => 'Movies & TV', 'slug' => 'movies-tv', 'description' => 'Movies and TV shows', 'parent_slug' => 'books-media', 'sort_order' => 2],
            ['name' => 'Music', 'slug' => 'music', 'description' => 'Music albums and singles', 'parent_slug' => 'books-media', 'sort_order' => 3],
        ];

        foreach ($categories as $categoryData) {
            $parentId = null;
            if (isset($categoryData['parent_slug'])) {
                $parent = Category::where('slug', $categoryData['parent_slug'])->first();
                $parentId = $parent ? $parent->id : null;
                unset($categoryData['parent_slug']);
            }

            Category::firstOrCreate(
                ['slug' => $categoryData['slug']],
                array_merge($categoryData, [
                    'parent_id' => $parentId,
                    'is_active' => true,
                ])
            );
        }
    }
}