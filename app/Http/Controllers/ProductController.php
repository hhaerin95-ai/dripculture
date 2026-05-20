<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Product::with(['category', 'images' => fn($q) => $q->where('is_primary', 1), 'variants'])
            ->where('status', 'Active');

        if ($request->filled('cat')) {
            $query->where('category_id', $request->cat);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }

        $query->orderBy(match ($request->sort) {
            'price_asc', 'price_desc' => 'base_price',
            default => 'created_at',
        }, $request->sort === 'price_asc' ? 'asc' : 'desc');

        $products = $query->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'variants', 'images']);
        return view('products.show', compact('product'));
    }
}
