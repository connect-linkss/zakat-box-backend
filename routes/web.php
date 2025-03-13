<?php

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\ZakatController;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Artisan;


Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return response()->json(['message' => 'Storage link created successfully']);
})->name('storage-link');


Route::get('/', [ZakatController::class, 'donate'])->name('donate');
Route::get('/test', [ZakatController::class, 'test'])->name('test');
Route::get('/data', [ZakatController::class, 'data'])->name('data');
Route::get('/donate-list', [ZakatController::class, 'index'])->name('index');
Route::get('/donate-list-customer', [ZakatController::class, 'indexCustomer'])->name('indexCustomer');
Route::post('/store', [ZakatController::class, 'store'])->name('store');


Route::get('/cache', function () {
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');
    return response()->json(['message' => 'Cache generated successfully']);
})->name('cache');

Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    return response()->json(['message' => 'Cache cleared successfully']);
})->name('clear-cache');

Route::get('authentication-failed', function () {
    $errors = [];
    array_push($errors, ['code' => 'auth-001', 'message' => 'Unauthenticated.']);
    return response()->json([
        'errors' => $errors
    ], 401);
})->name('authentication-failed');
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('/code/captcha/{tmp}', [LoginController::class, 'captcha'])->name('default-captcha');
        Route::get('login', [LoginController::class, 'login'])->name('login');
        Route::post('login', [LoginController::class, 'submit']);
        Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    })->middleware(RedirectIfAuthenticated::class);
    Route::group(['middleware' => AdminMiddleware::class], function () {
        Route::get('/', [SystemController::class, 'index'])->name('dashboard');
        Route::post('order-stats', [SystemController::class, 'orderStats'])->name('order-stats');
        Route::get('settings', [SystemController::class, 'settings'])->name('settings');
        Route::post('settings', [SystemController::class, 'settingsUpdate'])->name('setting-update');
        Route::post('settings-password', [SystemController::class, 'settingsPasswordUpdate'])->name('settings-password');

        Route::group(['prefix' => 'donate', 'as' => 'donate.'], function () {
            Route::get('filter', [SystemController::class, 'data'])->name('filter');
            Route::get('status/{id}/{status}', [SystemController::class, 'status'])->name('status');
        });
    });
});
