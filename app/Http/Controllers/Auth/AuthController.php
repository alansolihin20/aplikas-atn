<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {   
        return view('auth.login');
        if (auth()->check()) {
        return redirect('/dashboard/admin'); // atau sesuai role
    }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

       
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            // Redirect sesuai role
            return match ($user->role) {
                default   => redirect('/dashboard/superadmin'),
                'admin'   => redirect('/dashboard/admin'),
                'teknisi' => redirect('/dashboard/teknisi'),
            };
        };
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);

        
    }

    public function logout( Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // Redirect ke halaman login setelah logout
        return redirect('/login')->with('success', 'Berhasil logout');
        // return redirect('/login');
    }

}