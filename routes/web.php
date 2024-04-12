<?php

use App\Http\Controllers\DeportesController;
use App\Http\Controllers\LigasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ViewController;
use App\Models\Ligas;


Route::controller(ViewController::class)->group(function () {
    Route::get('/', 'getWelcome')->name('welcome');
    Route::get('faq', 'getFAQ')->name('faq')->middleware('auth');
    Route::get('create', 'getCreate')->name('create')->middleware('auth');
});

Route::controller(LigasController::class)->group(function () {
    Route::get('liga/{deporte}', 'LigaDeporte')->middleware('auth');
});


Route::controller(LoginController::class)->group(function () {
    Route::post('validar-register', 'store')->name('validar-register');
    Route::post('login',  'login')->name('login');
    Route::post('logout',  'logout')->name('logout');
    Route::get('login', 'getLogin')->middleware('guest')->name('login');
    Route::get('registro', 'register')->name('registro')->middleware('guest');
});


