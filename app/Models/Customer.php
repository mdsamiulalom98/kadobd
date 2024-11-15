<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use HasFactory;

    protected $guard = 'customer';
    protected $fillable = [
        'name', 'email', 'password',
    ];
    protected $hidden = [
      'password', 'remember_token',
    ];

    public function cust_area()
    {
        return $this->belongsTo(District::class,'area');
    }
    public function orders()
    {
        return $this->hasMany(Order::class,'customer_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class,'customer_id');
    }
    public function withdraws()
    {
        return $this->hasMany(Withdraw::class,'customer_id');
    }
    public function profits()
    {
        return $this->hasMany(CustomerProfit::class,'customer_id');
    }
}
