<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function send(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|min:3',
            'email'   => 'required|email',
            'subject' => 'required|string|min:5',
            'message' => 'required|string|min:20',
        ]);

        // In production: Mail::to('hello@dripculture.my')->send(new ContactMail($request->all()));

        return back()->with('success', '✅ Message sent! We will reply within 24 hours.');
    }
}
