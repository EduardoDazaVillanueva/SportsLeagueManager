<?php

use App\Http\Controllers\LigasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ViewController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;


Route::controller(ViewController::class)->group(function () {
    Route::get('/', 'getWelcome')->name('welcome');
    Route::get('faq', 'getFAQ')->name('faq');
    Route::get('perfil', 'getPerfil')->name('perfil');
});


Route::controller(LigasController::class)->group(function () {
    Route::get('liga/deporte/{deporte}', 'LigaDeporte')->middleware('auth');
    
    Route::get('liga/{liga}/Clasificacion', 'ligaClasificacion')->middleware('auth');
    Route::get('liga/{liga}/Jugadores', 'ligaJugadores')->middleware('auth');
    Route::get('liga/{liga}/Partidos', 'ligaPartidos')->middleware('auth');

    Route::get('liga/{liga}', 'show')->middleware('auth')->whereNumber('liga');
    Route::get('liga/crear/{deporteID}', 'create');
    Route::post('liga', 'store')->name('crearLiga')->middleware('auth');
    Route::post('/liga/{liga}/inscribirse/{userId}','inscribirse')->name('liga.inscribirse')->middleware('auth');
    
});


Route::controller(LoginController::class)->group(function () {
    Route::post('validar-register', 'store')->name('validar-register');
    Route::post('login',  'login')->name('login');
    Route::post('logout', 'logout')->name('logout')->middleware('auth')->withoutMiddleware('guest');
    Route::get('login', 'getLogin')->middleware('guest')->name('login');
    Route::get('registro', 'register')->name('registro')->middleware('guest');
});