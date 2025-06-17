<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ShoppingFuelController;
use App\Http\Controllers\SharedController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PasswordResetController;


Route::get('/', function () {
    return view('home');
});
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/login', [LoginController::class, 'login']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::post('/shopping-fuel', [ShoppingFuelController::class, 'store'])->name('shoppingFuel.store');
Route::get('/shopping-fuel/{id}', [ShoppingFuelController::class, 'show'])->name('shoppingFuel.show');
Route::put('/shopping-fuel/{id}', [ShoppingFuelController::class, 'update'])->name('shoppingFuel.update');
Route::delete('/shopping-fuel/{id}', [ShoppingFuelController::class, 'destroy'])->name('shoppingFuel.destroy');
Route::get('/shopping-fuel', [ShoppingFuelController::class, 'index']);

Route::get('/shared', [SharedController::class, 'index']);

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
Route::post('/register/confirm', [RegisterController::class, 'confirm'])->name('register.confirm');
Route::get('/register/confirm', [RegisterController::class, 'confirm'])->name('register.confirm');

Route::get('/password-reset', [PasswordResetController::class, 'showRequestForm'])->name('password.reset');
Route::post('/password-reset', [PasswordResetController::class, 'sendResetLink'])->name('password.reset.send');

Route::get('/password-reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/password-reset/{token}', [PasswordResetController::class, 'resetPassword'])->name('password.reset.complete');