<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Product::with(['category', 'variants', 'images'])
            ->where('status', 'Active')
            ->take(4)
            ->get();

        return view('home', compact('featured'));
    }
}