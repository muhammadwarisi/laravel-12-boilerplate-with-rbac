<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    /**
     * Display the login page
     */
    public function showLogin(): View
    {
        return view('dashboard.pages.auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request): RedirectResponse
    {
        // Validasi input dari form
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
        ]);

        // Jika ada checkbox "Remember Me", set remember-me
        $remember = $request->has('remember');

        // Attempt login
        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']], $remember)) {
            // Regenerate session untuk security
            $request->session()->regenerate();
            Alert::success('Login Berhasil', 'Selamat datang ' . Auth::user()->name . '!');

            return to_route('dashboard')->with('success', 'Login berhasil!');
        }

        Alert::error('Login Gagal', 'Email atau password tidak sesuai.');
        // Login gagal
        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Email atau password tidak sesuai.',
            ]);
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        // Invalidate session dan regenerate token
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Alert::success('Logout Berhasil', 'Anda telah berhasil logout.');

        return redirect()->route('login')->with('success', 'Logout berhasil!');
    }
}
