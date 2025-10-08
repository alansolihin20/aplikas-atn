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


use App\Http\Controllers\Shift\AttendanceController;

Route::post('/check-in', [AttendanceController::class, 'checkIn'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/check-out', [AttendanceController::class, 'checkOut'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);



