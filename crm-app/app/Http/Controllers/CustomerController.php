<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Product;
use App\Models\CustomerService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Carbon;

class CustomerController
{
    public function index()
    {
        $customers = Customer::with('services.product')->get();
        return View::make('customers.index', compact('customers'));
    }

    public function create()
    {
        $products = Product::all();
        return View::make('customers.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name'=>'required','phone'=>'nullable','email'=>'nullable','address'=>'nullable']);
        $customer = Customer::create($data + ['joined_at'=>Carbon::now()]);

        if ($request->filled('product_id')) {
            CustomerService::create(['customer_id'=>$customer->id,'product_id'=>$request->product_id,'start_date'=>Carbon::now(),'monthly_fee'=> $request->monthly_fee ?? 0,'status'=>'active']);
        }

        return Redirect::route('customers.index');
    }
}
