<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            session(['user_id' => $user->id]);
            return redirect()->route('home');
        }

        return back()->withErrors(['email' => 'Credentials do not match our records.']);
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login');
    }
}
