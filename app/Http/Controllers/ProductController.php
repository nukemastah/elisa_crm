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
        $q = request('q');
        $sort = request('sort', 'id');
        $dir = request('dir', 'desc');

        $allowedSorts = ['id','code','name','monthly_price'];
        if (!in_array($sort, $allowedSorts)) { $sort = 'id'; }
        $dir = strtolower($dir) === 'asc' ? 'asc' : 'desc';

        $query = Product::query();
        if ($q) {
            $query->where(function($builder) use ($q) {
                $builder->where('code','ILIKE',"%$q%")
                        ->orWhere('name','ILIKE',"%$q%")
                        ->orWhere('description','ILIKE',"%$q%");
            });
        }

        $products = $query->orderBy($sort, $dir)->paginate(10)->appends(request()->query());
        return View::make('products.index', compact('products','q','sort','dir'));
    }

    public function create()
    {
        return View::make('products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(["code"=>"required","name"=>"required","description"=>"nullable","monthly_price"=>"nullable"]);
        Product::create($data);
        return Redirect::route('products.index')->with('success','Product created');
    }

    public function edit(Product $product)
    {
        return View::make('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->only(['code','name','description','monthly_price']));
        return Redirect::route('products.index')->with('success','Product updated');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return Redirect::route('products.index')->with('success','Product deleted');
    }
}
