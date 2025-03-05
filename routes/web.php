<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('customers.index');
});

Route::get('customers/trash', [CustomerController::class, 'trashIndex'])->name('customers.trash');
Route::post('customers/trash/{customer}/restore', [CustomerController::class, 'restore'])->name('customers.restore');
Route::delete('customers/trash/{customer}/delete', [CustomerController::class, 'forceDestroy'])->name('customers.force.destroy');
Route::resource('customers', CustomerController::class);
