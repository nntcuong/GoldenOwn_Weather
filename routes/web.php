<?php

use App\Http\Controllers\EmailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\WeatherCurrentController;

// Chuyển hướng trang chủ đến trang weather
Route::get('/', function () {
    return redirect('/weather');
});

// Route cho dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Group các route cần xác thực (auth middleware)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route cho weather
Route::match(["get","post"], "/weather", [WeatherController::class, "index"])->name("weather.form");

// Route cho subscribe và unsubscribe
Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscribe');
Route::post('/unsubscribe', [SubscriptionController::class, 'unsubscribe'])->name('unsubscribe');

// Route cho email
Route::match(["get","post"], "/email", [EmailController::class, "index"])->name("email.form");

require __DIR__.'/auth.php';
