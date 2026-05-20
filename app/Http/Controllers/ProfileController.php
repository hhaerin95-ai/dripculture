<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private array $states = [
        'Johor','Kedah','Kelantan','Melaka','Negeri Sembilan','Pahang',
        'Penang','Perak','Perlis','Sabah','Sarawak','Selangor',
        'Terengganu','Kuala Lumpur','Putrajaya','Labuan',
    ];

    public function edit()
    {
        $user   = Auth::user();
        $states = $this->states;
        return view('profile.edit', compact('user', 'states'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|min:3',
            'phone'    => 'required|regex:/^01[0-9]{8,9}$/',
            'address'  => 'required|string|min:10',
            'postcode' => 'required|regex:/^\d{5}$/',
            'state'    => 'required|in:' . implode(',', $this->states),
        ], [
            'phone.regex'    => 'Enter valid Malaysian phone number.',
            'postcode.regex' => 'Postcode must be 5 digits.',
        ]);

        Auth::user()->update($request->only('name', 'phone', 'address', 'postcode', 'state'));

        return redirect()->route('profile.edit')->with('success', '✅ Profile updated successfully!');
    }
}
