<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AuthController extends Controller

{
    public function showLoginForm()
    {

        if (Auth::check()) {
            return redirect('/dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                throw ValidationException::withMessages([
                    'email' => ['Email tidak terdaftar.'],
                ]);
            }

            if ($user->role !== 'admin') {
                throw ValidationException::withMessages([
                    'email' => ['Akun ini tidak memiliki akses sebagai admin.'],
                ]);
            }

            if (!Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->filled('remember'))) {
                throw ValidationException::withMessages([
                    'password' => ['Password salah.'],
                ]);
            }

            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Login error: ' . $e->getMessage());
            return back()->withErrors([
                'email' => 'Terjadi kesalahan pada server. Silakan coba lagi.',
            ])->withInput($request->only('email', 'remember'));
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
