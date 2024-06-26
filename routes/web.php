<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContactGroupController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SmsController;
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
    Route::post('contacts/delete', [
        ContactController::class, 'delete'
    ])->name('contacts.delete');
    Route::get('contacts/export/download', [
        ContactController::class, 'exportDownload'
    ])->name('contacts.export.download');
    Route::post('contacts/import/upload', [
        ContactController::class, 'importUpload'
    ])->name('contacts.import.upload');


    Route::resource('templates', TemplateController::class, [
        'name' => 'templates'
    ]);
    Route::post('templates/delete', [
        TemplateController::class, 'delete'
    ])->name('templates.delete');


    Route::resource('sms', SmsController::class, [
        'name' => 'sms'
    ]);
});
