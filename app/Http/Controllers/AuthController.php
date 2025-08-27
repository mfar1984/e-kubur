<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Activitylog\Facades\Activity;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Log successful login with IP and User Agent
            activity('auth')
                ->event('login')
                ->causedBy(Auth::user())
                ->performedOn(Auth::user())
                ->withProperties([
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ])
                ->log('Pengguna berjaya log masuk');
            return redirect()->intended('/overview');
        }

        // Log failed login attempt
        activity('auth')
            ->event('login_failed')
            ->withProperties([
                'email' => $request->input('email'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log('Percubaan log masuk gagal');

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Log logout before invalidating session
        if (Auth::check()) {
            activity('auth')
                ->event('logout')
                ->causedBy(Auth::user())
                ->performedOn(Auth::user())
                ->withProperties([
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ])
                ->log('Pengguna log keluar');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
