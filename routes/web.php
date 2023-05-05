<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::middleware(['auth', 'verified'])->group(function() {
	Route::get('/dashboard', function () {
		return view('dashboard');
	})->name('dashboard');
	Route::get('/locations', \App\Http\Livewire\Pages\Locations\Index::class)->name('locations.index');
	Route::get('/products', \App\Http\Livewire\Pages\Products\Index::class)->name('products.index');
	Route::get('/items', \App\Http\Livewire\Pages\Items\Index::class)->name('items.index');
	Route::get('/production-orders', \App\Http\Livewire\Pages\ProductionOrders\Index::class)->name('production-orders.index');
	Route::get('/users', \App\Http\Livewire\Pages\Users\Index::class)->name('users.index');
	Route::get('/suppliers', \App\Http\Livewire\Pages\Suppliers\Index::class)->name('suppliers.index');
	Route::get('/destinations', \App\Http\Livewire\Pages\Destinations\Index::class)->name('destinations.index');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
