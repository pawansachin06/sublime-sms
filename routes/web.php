<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.index');
})->name('home');

Route::get('/welcome', function () {
    return view('welcome');
});


Route::middleware([
    'auth:sanctum', config('jetstream.auth_session'), 'verified',
])->group(function () {

    Route::get('/dashboard', [
        PageController::class, 'dashboard'
    ])->name('dashboard');

});
