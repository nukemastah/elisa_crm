<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use App\Models\User;

class UsersController
{
    public function index()
    {
        $users = User::orderByDesc('id')->get();
        return View::make('users.index', compact('users'));
    }
}
