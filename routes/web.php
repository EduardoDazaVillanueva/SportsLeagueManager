<?php

use App\Http\Controllers\LigasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ViewController;
use Illuminate\Support\Facades\Mail;


Route::controller(ViewController::class)->group(function () {
    Route::get('/', 'getWelcome')->name('welcome');
    Route::get('faq', 'getFAQ')->name('faq');
    Route::get('create', 'getCreate')->name('create')->middleware('auth', 'verified');
});

Route::controller(LigasController::class)->group(function () {
    Route::get('liga/deporte/{deporte}', 'LigaDeporte')->middleware('auth');
    Route::get('liga/{liga}', 'show')->middleware('auth');
    Route::get('/Clasificacion', 'ligaClasificacion')->middleware('auth');
    Route::get('liga/{liga}/Jugadores', 'ligaJugadores')->middleware('auth');
});


Route::controller(LoginController::class)->group(function () {
    Route::post('validar-register', 'store')->name('validar-register');
    Route::post('login',  'login')->name('login');
    Route::post('logout',  'logout')->name('logout');
    Route::get('login', 'getLogin')->middleware('guest')->name('login');
    Route::get('registro', 'register')->name('registro')->middleware('guest');
});
