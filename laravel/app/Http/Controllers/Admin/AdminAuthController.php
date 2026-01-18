<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    /**
     * Show admin login page (if not logged in).
     */
    public function showLoginForm()
    {
        // If already logged in as admin → redirect
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        // If logged in but NOT admin → deny
        if (Auth::check() && !Auth::user()->is_admin) {
            abort(403, 'Nemaš administratorska prava.');
        }

        return view('admin.auth.login');
    }

    /**
     * Handle admin login attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            $request->session()->regenerate();

            // Allow only real admins
            if (Auth::user()->is_admin) {
                return redirect()->route('admin.dashboard');
            }

            // Logged in BUT not admin → auto logout
            Auth::logout();

            return back()->withErrors([
                'email' => 'Ovaj korisnik nema administratorska prava.',
            ])->onlyInput('email');
        }

        // Login failed
        return back()->withErrors([
            'email' => 'Neispravni podatci.',
        ])->onlyInput('email');
    }

    /**
     * Admin logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // return to homepage
    }
}
