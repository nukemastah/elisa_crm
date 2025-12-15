<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    public $timestamps = false;
    protected $fillable = ['project_id','approved_by','approved','notes','created_at'];
}
