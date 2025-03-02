<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('customers.index');
});

Route::resource('customers', CustomerController::class);
