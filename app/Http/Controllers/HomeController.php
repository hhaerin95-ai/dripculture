<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Product::with([
            'category',
            'images' => fn($q) => $q->where('is_primary', 1)
        ])
        ->where('status', 'Active')
        ->get();

        return view('home', compact('featured'));
    }
}