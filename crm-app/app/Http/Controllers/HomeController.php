<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Product;
use App\Models\Project;
use Illuminate\Support\Facades\View;

class HomeController
{
    public function index()
    {
        $leads = Lead::latest()->limit(5)->get();
        $projects = Project::latest()->limit(5)->get();
        $customers = \App\Models\Customer::latest()->limit(5)->get();
        $products = Product::all();
        return View::make('home', compact('leads','projects','products','customers'));
    }
}
