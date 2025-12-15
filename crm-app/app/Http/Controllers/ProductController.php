<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

class ProductController
{
    public function index()
    {
        $products = Product::all();
        return View::make('products.index', compact('products'));
    }

    public function create()
    {
        return View::make('products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(["code"=>"required","name"=>"required","description"=>"nullable","monthly_price"=>"nullable"]);
        Product::create($data);
        return Redirect::route('products.index');
    }

    public function edit(Product $product)
    {
        return View::make('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->only(['code','name','description','monthly_price']));
        return Redirect::route('products.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return Redirect::route('products.index');
    }
}
