<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Product::with(['category', 'images' => fn($q) => $q->where('is_primary', 1)])
            ->where('status', 'Active')
            ->whereHas('variants', fn($q) => $q->where('stock_qty', '>', 0))
            ->limit(6)
            ->get();

        $categories = Category::all();

        return view('home', compact('featured', 'categories'));
    }
}
