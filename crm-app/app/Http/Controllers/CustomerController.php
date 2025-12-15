<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Lead;
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
        $leads = Lead::all();
        return View::make('customers.create', compact('products', 'leads'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name'=>'required','phone'=>'nullable','email'=>'nullable','address'=>'nullable','lead_id'=>'nullable','joined_at'=>'required|date']);
        $customer = Customer::create($data);

        // Handle multiple services
        if ($request->has('services')) {
            foreach ($request->services as $productId => $serviceData) {
                if (isset($serviceData['product_id'])) {
                    CustomerService::create([
                        'customer_id' => $customer->id,
                        'product_id' => $serviceData['product_id'],
                        'start_date' => $serviceData['start_date'] ?? Carbon::now(),
                        'monthly_fee' => $serviceData['monthly_fee'] ?? 0,
                        'status' => $serviceData['status'] ?? 'active'
                    ]);
                }
            }
        }

        return Redirect::route('customers.index');
    }
}
