<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'], // Menggunakan NBM atau NIS
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Redirect berdasarkan role
            $role = Auth::user()->role;
            
            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'guru') {
                return redirect()->route('guru.dashboard'); // Arahkan ke dashboard Guru
            }
            
            // Jika bukan admin dan bukan guru, pasti student
            return redirect()->route('student.dashboard');
        }

        return back()->withErrors([
            'username' => 'NBM/NIS atau Password salah!',
        ])->onlyInput('username');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}