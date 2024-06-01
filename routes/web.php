<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\SocialLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.index');
})->name('home');

Route::get('/welcome', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login/redirect/google', [
        SocialLoginController::class, 'googleRedirect'
    ])->name('login.google');
    Route::get('/login/callback/google', [
        SocialLoginController::class, 'googleCallback'
    ]);
});


Route::middleware([
    'auth:sanctum', config('jetstream.auth_session'), 'verified',
])->group(function () {

    Route::get('/dashboard', [
        PageController::class, 'dashboard'
    ])->name('dashboard');

});
