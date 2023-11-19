<?php

use App\Http\Controllers\Transaction\PermitController;
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
    // get controller from PermitController
    Route::resource('permit', PermitController::class);
    Route::post('permit/soft-delete/{id}', [PermitController::class, 'softDestroy'])->name('permit.softDelete');
});
