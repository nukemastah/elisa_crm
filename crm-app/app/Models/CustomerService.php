<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerService extends Model
{
    protected $fillable = ['customer_id','product_id','start_date','end_date','monthly_fee','status'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
