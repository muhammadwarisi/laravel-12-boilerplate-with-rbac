<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RegisterController extends Controller
{
    /**
     * Display the register page
     */
    public function showRegister(): View
    {
        return view('dashboard.pages.auth.register');
    }

    /**
     * Handle register request
     */
    public function register(Request $request): RedirectResponse
    {
        // Validasi input dari form
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama harus diisi',
            'name.string' => 'Nama harus berupa teks',
            'name.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
        ]);

        try {
            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
            ]);

            // Auto login setelah register
            Auth::login($user);

            // Regenerate session untuk security
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))->with('success', 'Akun berhasil dibuat! Selamat datang.');
        } catch (\Exception $e) {
            return back()
                ->withInput($request->only('name', 'email'))
                ->withErrors([
                    'general' => 'Terjadi kesalahan saat membuat akun. Silakan coba lagi.',
                ]);
        }
    }
}
