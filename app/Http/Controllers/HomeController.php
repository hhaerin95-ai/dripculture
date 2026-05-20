<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;

class HomeController extends Controller
{
    public function index()
    {
        $featured = collect();

        return view('home', compact('featured'));
    }
}