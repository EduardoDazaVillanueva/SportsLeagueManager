<?php

use App\Http\Controllers\LigasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\StripeController;
use App\Mail\VerificarElEmail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\VerificationController;


Route::controller(ViewController::class)->group(function () {

    Route::get('/', 'getWelcome')->name('welcome');
    Route::get('faq', 'getFAQ')->name('faq');
    Route::get('cookies', 'getCookies')->name('cookies');
    Route::get('perfil/{user}', 'getPerfil')->name('perfil');
    Route::get('/resposabilidad', 'getResponsabilidad');
});

Route::controller(LigasController::class)->group(function () {
    Route::get('liga/deporte/{deporte}', 'LigaDeporte')->middleware('auth', 'verified')->name('liga.ligaDeporte');
    Route::get('liga/{liga}/Clasificacion', 'ligaClasificacion')->middleware('auth', 'verified');
    Route::get('liga/{liga}/Jugadores', 'ligaJugadores')->middleware('auth', 'verified');
    Route::get('liga/{liga}/Partidos', 'ligaPartidos')->name('liga.partidos')->middleware('auth', 'verified');
    Route::get('liga/{liga}', 'show')->middleware('auth', 'verified')->whereNumber('liga')->name('liga.show');
    Route::get('liga/crear/{deporteID}', 'create');
    Route::get('liga/editar/{liga}', 'edit');
    Route::get('liga/invitar/{liga}', 'invitar')->middleware('auth', 'verified');
    Route::get('liga/{liga}/resultado/{idPartido}', 'resultado');
    Route::get('liga/{liga}/crearEquipo', 'crearEquipo')->name('liga.crearEquipo')->middleware('auth', 'verified');

    Route::post('liga', 'store')->name('crearLiga')->middleware('auth', 'verified');
    Route::post('/liga/{liga}/inscribirse/{userId}', 'inscribirse')->name('liga.inscribirse')->middleware('auth', 'verified');
    Route::post('/liga/{liga}/jugarJornada/{userId}', 'jugarJornada')->name('liga.jugarJornada')->middleware('auth', 'verified');
    Route::post('/liga/{liga}/addResultado/{partido}', 'addResultado')->name('liga.addResultado')->middleware('auth', 'verified');
    Route::post('enviarInvitacion/{liga}', 'enviarInvitacion')->name('liga.enviarInvitacion');
    Route::post('/liga/{liga}/storeEquipo/{userId}', 'storeEquipo')->name('liga.storeEquipo')->middleware('auth', 'verified');

    Route::put('ligas/{liga}', 'update')->name('ligas.update');
});

Route::controller(LoginController::class)->group(function () {
    Route::post('validar-register', 'store')->name('validar-register');
    Route::post('login',  'login')->name('login');
    Route::post('logout', 'logout')->name('logout')->middleware('auth')->withoutMiddleware('guest');
    Route::post('getUser', 'getUser')->name('getUser');
    Route::post('reenviarCorreo', 'reenviarCorreo')->name('reenviarCorreo');

    Route::get('login', 'getLogin')->middleware('guest')->name('login');
    Route::get('registro', 'register')->name('registro')->middleware('guest');
});

Route::fallback([ViewController::class, 'get404']);

Route::get('/create-checkout-session', [StripeController::class, 'createCheckoutSession'])->name('checkout.session');
Route::get('/success', function () {
    return view('success');
})->name('success');
Route::get('/cancel', function () {
    return view('cancel');
})->name('cancel');
