<?php

use App\Http\Controllers\CompraController;
use App\Http\Controllers\LigasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ViewController;

use App\Http\Controllers\PdfController;

Route::controller(ViewController::class)->group(function () {
    Route::get('/', 'getWelcome')->name('welcome');
    Route::get('faq', 'getFAQ')->name('faq');
    Route::get('cookies', 'getCookies')->name('cookies');
    Route::get('perfil/{user}', 'getPerfil')->name('perfil');
    Route::get('/resposabilidad', 'getResponsabilidad');
    Route::get('/sobreNosotros', 'getSobreNosotros');
    Route::fallback('get404');    
});

Route::controller(LigasController::class)->group(function () {
    Route::get('liga/deporte/{deporte}', 'LigaDeporte')->middleware('auth', 'verified')->name('liga.ligaDeporte');
    Route::get('liga/{liga}/Clasificacion', 'ligaClasificacion')->middleware('auth', 'verified');
    Route::get('liga/{liga}/Jugadores', 'ligaJugadores')->middleware('auth', 'verified');
    Route::get('liga/{liga}/Partidos', 'ligaPartidos')->name('liga.partidos')->middleware('auth', 'verified');
    Route::get('liga/{liga}', 'show')->middleware('auth', 'verified')->whereNumber('liga')->name('liga.show');
    Route::get('liga/crear/{deporteID}', 'create')->middleware('EsOrganizador');
    Route::get('liga/editar/{liga}', 'edit')->middleware('EsOrganizadorDeLiga');
    Route::get('liga/invitar/{liga}', 'invitar')->middleware('auth', 'verified');
    Route::get('liga/{liga}/resultado/{idPartido}', 'resultado')->middleware('ParticipaPartido');
    Route::get('liga/{liga}/crearEquipo', 'crearEquipo')->name('liga.crearEquipo')->middleware('auth', 'verified');
    Route::get('liga/{liga}/invitarEquipo', 'invitarEquipo')->name('liga.invitarEquipo')->middleware('auth', 'verified');
    Route::get('liga/{liga}/unirseEquipo', 'unirseEquipo')->name('liga.unirseEquipo')->middleware('auth', 'verified');


    Route::post('liga', 'store')->name('crearLiga')->middleware('auth', 'verified');
    Route::post('/liga/{liga}/inscribirse/{userId}', 'inscribirse')->name('liga.inscribirse')->middleware('auth', 'verified');
    Route::post('/liga/{liga}/jugarJornada/{userId}', 'jugarJornada')->name('liga.jugarJornada')->middleware('auth', 'verified');
    Route::post('/liga/{liga}/addResultado/{partido}', 'addResultado')->name('liga.addResultado')->middleware('auth', 'verified');
    Route::post('enviarInvitacion/{liga}', 'enviarInvitacion')->name('liga.enviarInvitacion');
    Route::post('/liga/{liga}/storeEquipo/{userId}', 'storeEquipo')->name('liga.storeEquipo')->middleware('auth', 'verified');
    Route::post('/liga/{liga}/ConfrimarCodigoEquipo', 'ConfrimarCodigoEquipo')->name('liga.ConfrimarCodigoEquipo')->middleware('auth', 'verified');

    Route::put('ligas/{liga}', 'update')->name('ligas.update')->middleware('EsOrganizadorDeLiga');
});

Route::controller(LoginController::class)->group(function () {
    Route::get('login', 'getLogin')->middleware('guest')->name('login');
    Route::get('registro', 'register')->name('registro')->middleware('guest');
    Route::get('editar/{user}', 'edit')->middleware('auth', 'verified', 'EresTu');

    Route::post('validar-register', 'store')->name('validar-register');
    Route::post('login',  'login')->name('login');
    Route::post('logout', 'logout')->name('logout')->middleware('auth')->withoutMiddleware('guest');
    Route::post('getUser', 'getUser')->name('getUser');
    Route::post('reenviarCorreo', 'reenviarCorreo')->name('reenviarCorreo');

    Route::put('editar/{user}', 'update')->name('user.update')->middleware('auth', 'verified', 'EresTu');
});

Route::controller(CompraController::class)->group(function () {
    Route::get('/checkout/{producto}', 'checkout')->name('compra.checkout')->middleware('auth', 'verified', 'YaComprado');
    Route::get('/paymentCallback/{producto}', 'paymentCallback')->name('paymentCallback')->middleware('auth', 'verified');

    Route::post('/processPayment', 'processPayment')->name('processPayment')->middleware('auth', 'verified');
});


Route::get('/verify-email/{user}/{token}', function (App\Models\User $user, $token) {
    if ($user->verificar_token === $token) {
        $user->email_verified_at = now();
        $user->save();
        return redirect('/login')->with('success', 'Tu correo ha sido verificado. Ahora puedes iniciar sesión.');
    } else {
        return redirect('/login')->with('error', 'El enlace de verificación no es válido.');
    }
});

Route::get('/generate-send-pdf/{liga}', [PdfController::class, 'generateAndSendPdf'])->name("enviarPDF");

