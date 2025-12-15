<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Product;
use App\Models\Project;

class HomeController
{
    public function index()
    {
        $leads = Lead::latest()->limit(5)->get();
        $projects = Project::latest()->limit(5)->get();
        $products = Product::all();
        return view('home', compact('leads','projects','products'));
    }
}
