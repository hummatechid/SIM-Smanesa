<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('get-api-testing', function() {
    $data = \App\Models\ViolationType::orderBy('created_at', 'asc')->get();
    return \Yajra\DataTables\Facades\DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($item) {
                return $item->name;
            })->addColumn('score', function($item) {
                return $item->email;
            })->addColumn('action', function($item) {
                return '<button type="button" class="btn btn-primary">Tombol</button>';
            })->rawColumns(['action'])
            ->make(true);
});
