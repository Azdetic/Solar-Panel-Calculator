<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect('/home');
});

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::view('/about', 'about')->name('about');

Route::get('/calculator', \App\Livewire\Calculator::class)->name('calculator'); 

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);

// Additional Auth Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function() {
        if(in_array(auth()->user()->role, ['admin', 'vendor'])) {
            return redirect()->route('admin.dashboard');
        }
        // Redirect normal users to calculator or profile, they don't have a dashboard
        return redirect()->route('calculator');
    })->name('dashboard');

    Route::get('/admin/dashboard', \App\Livewire\AdminDashboard::class)->name('admin.dashboard');
    Route::get('/profile', \App\Livewire\UserProfile::class)->name('profile');
    Route::get('/messages', \App\Livewire\Messages::class)->name('messages');
});
