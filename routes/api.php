<?php

use App\Http\Controllers\SmsController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::any('/callback/sms/dlr', [
    SmsController::class, 'dlr_callback'
])->name('api.sms.callback.dlr');
