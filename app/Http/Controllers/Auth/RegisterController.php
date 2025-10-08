<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:superadmin,admin,teknisi',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>$request->password,
            'role' => $request->role,
        ]);

        return redirect('/login')->with('success', 'Registration successful. Please login.');
    }
}
