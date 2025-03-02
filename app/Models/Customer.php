<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['image', 'first_name', 'last_name', 'email', 'phone', 'bank_account_number', 'about'];
}
