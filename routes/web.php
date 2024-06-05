<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContactGroupController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\TemplateController;
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

    Route::resource('contact-groups', ContactGroupController::class, [
        'name' => 'contact-groups'
    ]);
    Route::post('contact-groups/delete', [
        ContactGroupController::class, 'delete'
    ])->name('contact-groups.delete');
    Route::get('contact-groups/export/download', [
        ContactGroupController::class, 'exportDownload'
    ])->name('contact-groups.export.download');

    Route::resource('contacts', ContactController::class, [
        'name' => 'contacts'
    ]);
    Route::resource('templates', TemplateController::class, [
        'name' => 'templates'
    ]);


});
