<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showForm()
    {
        if (Auth::check()) return redirect()->route('home');
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            $firstName = explode(' ', $user->name)[0];

            // Redirect admins (role_id = 1) to the admin panel
            if ((int) $user->role_id === 1) {
                session([
                    'admin_id'    => $user->id,
                    'admin_name'  => $user->full_name ?? $user->name,
                    'admin_email' => $user->email,
                ]);
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Welcome back, ' . $firstName . '!');
            }

            return redirect()->intended(route('home'))->with('success', "Welcome back, $firstName! 🔥");
        }

        return back()->withErrors(['email' => 'Invalid email or password.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('info', 'You have been logged out. See you soon! 👋');
    }
}