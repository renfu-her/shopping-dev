<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * Get products list with search and filters
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Product::with(['categories', 'images'])
            ->where('is_active', true);

        // Search by name or description
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        // Filter by price range
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by stock availability
        if ($request->has('in_stock') && $request->boolean('in_stock')) {
            $query->where('stock_quantity', '>', 0);
        }

        // Filter by featured products
        if ($request->has('featured') && $request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSortFields = ['name', 'price', 'created_at', 'updated_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination
        $perPage = min($request->get('per_page', 15), 50); // Max 50 items per page
        $products = $query->paginate($perPage);

        return ProductResource::collection($products);
    }

    /**
     * Get single product details
     */
    public function show(Product $product): JsonResponse
    {
        // Check if product is active
        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found or not available',
            ], 404);
        }

        $product->load(['categories', 'images']);

        return response()->json([
            'success' => true,
            'data' => new ProductResource($product),
        ]);
    }

    /**
     * Get featured products
     */
    public function featured(Request $request): AnonymousResourceCollection
    {
        $limit = min($request->get('limit', 10), 20); // Max 20 featured products

        $products = Product::with(['categories', 'images'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return ProductResource::collection($products);
    }

    /**
     * Get related products
     */
    public function related(Product $product, Request $request): AnonymousResourceCollection
    {
        $limit = min($request->get('limit', 5), 10); // Max 10 related products

        $relatedProducts = Product::with(['categories', 'images'])
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->whereHas('categories', function ($q) use ($product) {
                $q->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return ProductResource::collection($relatedProducts);
    }

    /**
     * Search products
     */
    public function search(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'q' => 'required|string|min:2|max:255',
        ]);

        $query = Product::with(['categories', 'images'])
            ->where('is_active', true);

        $searchTerm = $request->q;
        $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'like', "%{$searchTerm}%")
              ->orWhere('description', 'like', "%{$searchTerm}%")
              ->orWhere('short_description', 'like', "%{$searchTerm}%")
              ->orWhere('sku', 'like', "%{$searchTerm}%");
        });

        // Additional filters
        if ($request->has('category_id') && $request->category_id) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        $perPage = min($request->get('per_page', 15), 50);
        $products = $query->orderBy('name')->paginate($perPage);

        return ProductResource::collection($products);
    }

    /**
     * Get product categories
     */
    public function categories(): JsonResponse
    {
        $categories = \App\Models\Category::with('children')
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }
}
