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
        $q = request('q');
        $sort = request('sort','id');
        $dir = request('dir','desc');
        $allowedSorts = ['id','name','email','phone','joined_at'];
        if (!in_array($sort, $allowedSorts)) { $sort = 'id'; }
        $dir = strtolower($dir) === 'asc' ? 'asc' : 'desc';

        $query = Customer::with('services.product');
        if ($q) {
            $query->where(function($builder) use ($q) {
                $builder->where('name','ILIKE',"%$q%")
                        ->orWhere('email','ILIKE',"%$q%")
                        ->orWhere('phone','ILIKE',"%$q%")
                        ->orWhere('address','ILIKE',"%$q%");
            });
        }

        $customers = $query->orderBy($sort,$dir)->paginate(10)->appends(request()->query());
        return View::make('customers.index', compact('customers','q','sort','dir'));
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

        return Redirect::route('customers.index')->with('success','Customer created');
    }

    public function show(Customer $customer)
    {
        $customer->load('services.product', 'lead');
        return View::make('customers.show', compact('customer'));
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return Redirect::route('customers.index')->with('success','Customer deleted');
    }
}
