<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\IpController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::post('change-ip', [IpController::class, 'changeIp'])->name('change-ip');
    Route::prefix('dashboard')->group(function () {
    });
});

Route::get('/', [HomeController::class, 'landingPage'])->name('landing-page');

Auth::routes();
