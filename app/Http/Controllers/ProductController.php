<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants', 'images'])
            ->where('status', 'Active');

        if ($request->filled('cat')) {
            $query->where('category_id', $request->cat);
        }

        $products = $query->get();
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'variants', 'images']);
        return view('products.show', compact('product'));
    }
}