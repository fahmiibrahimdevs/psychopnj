<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/absensi', [App\Http\Controllers\Api\AbsensiController::class, 'store']);
Route::post('/door-lock', [App\Http\Controllers\Api\DoorLockController::class, 'store']);
