<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

class AuthController
{
    public function showLogin()
    {
        return View::make('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $request->session()->put('user_id', $user->id);
            return Redirect::route('home');
        }

        return Redirect::back()->withErrors(['email' => 'Credentials do not match our records.']);
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return Redirect::route('login');
    }
}
