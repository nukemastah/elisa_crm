<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = ['name','phone','email','address','source','status','assigned_to'];

    public function assigned()
    {
        return $this->belongsTo(User::class,'assigned_to');
    }
}
