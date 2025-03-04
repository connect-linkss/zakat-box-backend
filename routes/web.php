<?php

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\ZakatController;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\RedirectIfAuthenticated;


Route::get('/', [ZakatController::class, 'donate'])->name('donate');
Route::get('/data', [ZakatController::class, 'data'])->name('data');
Route::get('/donate-list', [ZakatController::class, 'index'])->name('index');
Route::post('/store', [ZakatController::class, 'store'])->name('store');


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
        });
    });
});
