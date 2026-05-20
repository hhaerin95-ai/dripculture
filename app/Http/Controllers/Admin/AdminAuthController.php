<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    /** Show the admin login form. */
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    /** Handle admin login. */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Look up user with Admin role (role_id = 1 = Admin)
        $admin = User::where('email', $credentials['email'])
                     ->where('role_id', 1)
                     ->first();

        if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Invalid credentials or insufficient privileges.');
        }

        // Store admin session — use 'id' as primary key
        session([
            'admin_id'    => $admin->id,
            'admin_name'  => $admin->full_name,
            'admin_email' => $admin->email,
        ]);

        return redirect()->route('admin.dashboard')
                         ->with('success', 'Welcome back, ' . $admin->full_name . '!');
    }

    /** Destroy admin session and redirect to login. */
    public function logout(Request $request)
    {
        $request->session()->forget(['admin_id', 'admin_name', 'admin_email']);

        return redirect()->route('admin.login')
                         ->with('success', 'You have been logged out successfully.');
    }
}
