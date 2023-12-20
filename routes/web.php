<?php

use App\Http\Controllers\HomeController;
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
    Route::prefix('dashboard')->group(function (){
    
    });
});

Route::get('/', function() {
    return view('admin.pages.blank.landing-page');
});

Auth::routes();

Route::get('scan-attendance', function() {
    return view('admin.pages.scanner.index');
})->name('scan.index');