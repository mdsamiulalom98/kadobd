<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id')->select('id','name','phone','bkash_no');
    }
}