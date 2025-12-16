<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name','phone','email','address','joined_at','lead_id'];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    public function services()
    {
        return $this->hasMany(CustomerService::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
