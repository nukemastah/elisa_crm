<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Product;
use App\Models\Lead;
use App\Models\Project;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(AdminUserSeeder::class);
        User::firstOrCreate(['email'=>'admin@example.com'],['name'=>'Admin User','password'=>Hash::make('password'),'role'=>'admin']);
        User::firstOrCreate(['email'=>'manager@example.com'],['name'=>'Manager One','password'=>Hash::make('password'),'role'=>'manager']);
        User::firstOrCreate(['email'=>'sales@example.com'],['name'=>'Sales One','password'=>Hash::make('password'),'role'=>'sales']);

        Product::firstOrCreate(['code'=>'FTTH-50'],['name'=>'FTTH 50 Mbps','description'=>'Fiber 50 Mbps','monthly_price'=>300000]);
        Product::firstOrCreate(['code'=>'FTTH-100'],['name'=>'FTTH 100 Mbps','description'=>'Fiber 100 Mbps','monthly_price'=>500000]);

        $lead = Lead::firstOrCreate(['email'=>'budi@mail.com'],['name'=>'Budi Customer','phone'=>'08123456789','address'=>'Jl. Kebon Jeruk','source'=>'Website','assigned_to'=>3]);

        Project::firstOrCreate(['lead_id'=>$lead->id],['product_id'=>1,'estimated_fee'=>1000000,'status'=>'pending']);
    }
}
