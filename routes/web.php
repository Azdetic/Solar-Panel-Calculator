<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect('/home');
});

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/calculator', \App\Livewire\Calculator::class)->name('calculator');

// Authentication Routes (Moved to /admin)
Route::get('/admin', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/admin', [App\Http\Controllers\AuthController::class, 'authenticate']);

// Additional Auth Routes
Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Dashboard Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function() {
        if(auth()->user()->role === 'admin') {
            return redirect('/admin/dashboard');
        }
        return redirect('/calculator'); // normal users just get calc
    })->name('dashboard');

    Route::get('/admin/dashboard', \App\Livewire\AdminDashboard::class)->name('admin.dashboard');
});
