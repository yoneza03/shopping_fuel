<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ShoppingFuelController;
use App\Http\Controllers\SharedController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\ShoppingController;
use App\Http\Controllers\PriceHistoryController;

Route::get('/home', [HomeController::class, 'index'])->name('home');

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

Route::get('/weather', [WeatherController::class, 'fetch'])->name('weather');

Route::middleware(['web'])->group(function () {
    Route::match(['get', 'post'], '/shopping-entry', [ShoppingController::class, 'entry'])->name('shopping.entry');
    Route::match(['get', 'post'], '/shopping-history', [ShoppingController::class, 'history'])->name('shopping.history');
    Route::post('/shopping-confirm', [ShoppingController::class, 'confirm'])->name('shopping.confirm');
    Route::get('/shopping-confirm/view', [ShoppingController::class, 'confirmView'])->name('shopping.confirm.view');
    Route::post('/shopping-store', [ShoppingController::class, 'store'])->name('shopping.store');
    Route::get('/shopping-clear', [ShoppingController::class, 'clear'])->name('shopping.clear');
});

Route::get('/price-history/{item}', [PriceHistoryController::class, 'show'])->name('price.history');

Route::get('/price-history-jump', function (Request $request) {
    $item = $request->query('item');    
    if (!$item) {
        return redirect()->back()->with('error', '商品名を入力してください');
    }

    return redirect()->route('price.history', ['item' => $item]);
})->name('price.history.jump');

Route::get('/fuel-entry', [FuelRecordController::class, 'create'])->name('fuel.entry');
Route::post('/fuel-store', [FuelRecordController::class, 'store'])->name('fuel.store');
Route::get('/fuel-history', [FuelRecordController::class, 'history'])->name('fuel.history');


Route::get('/camera', function () {
    return 'カメラ起動処理はここに実装予定です';
})->name('camera.start');

Route::get('/history', function () {
    return '履歴一覧はここに実装予定です';
})->name('history');

Route::get('/admin', function () {
    return '管理者画面はここに実装予定です';
})->name('admin');