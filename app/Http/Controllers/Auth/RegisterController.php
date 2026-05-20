<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    private array $states = [
        'Johor','Kedah','Kelantan','Melaka','Negeri Sembilan','Pahang',
        'Penang','Perak','Perlis','Sabah','Sarawak','Selangor',
        'Terengganu','Kuala Lumpur','Putrajaya','Labuan',
    ];

    public function showForm()
    {
        if (Auth::check()) return redirect()->route('home');
        $states = $this->states;
        return view('auth.register', compact('states'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|min:3',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|regex:/[A-Z]/|regex:/[0-9]/|confirmed',
            'phone'    => 'required|regex:/^01[0-9]{8,9}$/',
            'address'  => 'required|string|min:10',
            'postcode' => 'required|regex:/^\d{5}$/',
            'state'    => 'required|in:' . implode(',', $this->states),
        ], [
            'password.regex'  => 'Password must have at least 1 uppercase letter and 1 number.',
            'phone.regex'     => 'Enter a valid Malaysian phone number.',
            'postcode.regex'  => 'Postcode must be exactly 5 digits.',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
            'address'  => $request->address,
            'postcode' => $request->postcode,
            'state'    => $request->state,
        ]);

        return redirect()->route('login')->with('success', '🎉 Account created! Welcome to DRIP CULTURE. Please login.');
    }
}
