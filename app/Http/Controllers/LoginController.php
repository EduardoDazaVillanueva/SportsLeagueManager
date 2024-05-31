<?php

namespace App\Http\Controllers;

use App\Http\Controllers\EmailController;

use App\Mail\VerificarElEmail;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Deportes;
use Illuminate\Support\Facades\Storage;
use Exception;

class LoginController extends Controller
{
    /**
     * Función para registrar al usuario
     */
    public function store(Request $request)
    {
        $user = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'name')
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
            ],
            'logo' => [
                'image',
                'mimes:jpeg,jpg,png,gif'
            ],
            'telefono' => [
                'required',
                'regex:/^\+?[1-9]\d{1,14}$/'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'confirmed'
            ],
        ]);

        unset($user['password_confirmation']);

        try {
            if ($request->hasFile('logo')) {
                $path = Storage::disk('public')->putFile('imagenes', $request->file('logo'));
                $user['logo'] = basename($path);
            } else {
                $user['logo'] = 'perfil.webp';
            }
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al almacenar el archivo: ' . $e->getMessage()]);
        }

        $user['verificar_token'] = bin2hex(random_bytes(16));

        $user = User::create($user);

        // Enviar el correo de verificación
        $emailController = new EmailController();
        $emailController->enviarCorreoVerificacion($user);

        return redirect("/login")->with('success', 'El usuario ha sido creado con éxito. Revisa tu correo para verificar tu cuenta.');
    }

    /**
     * Enviar a la vista de register
     */
    public function register()
    {
        return view('user.register', [
            'deportes' => Deportes::all(),
            'user' => Auth::user()
        ]);
    }

    /**
     * Enviar a la vista de login
     */
    public function getLogin()
    {
        return view('user.login', [
            'deportes' => Deportes::all(),
            'user' => Auth::user()
        ]);
    }

    /**
     * Función para comprobar que el usuario exite y las credenciales son correctas
     */
    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);


        if (auth()->attempt($credenciales)) {

            $user = auth()->user();

            //Comprobar si tiene el email verificado
            if (is_null($user->email_verified_at)) {
                auth()->logout();
                return redirect()->back()->withErrors([
                    'email' => 'Debes verificar tu correo electrónico antes de iniciar sesión.',
                ]);
            }

            $request->session()->regenerate();

            return redirect('/');
        } else {
            return redirect()->back()->withErrors('error', 'Auth failed bad credentials');
        }
    }

    /**
     * Función para cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'));
    }

    /**
     * Envia a la vista de reenvio de email de verificación
     */
    public function reenviarCorreo()
    {
        return view('user.verificacion', [
            'deportes' => Deportes::all(),
            'user' => Auth::user()
        ]);
    }

    /**
     * Obtener el usuario para reenviarle el correo de verificación
     */
    public function getUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if ($user) {
            if (is_null($user->email_verified_at)) {
                $emailController = new EmailController();
                $emailController->enviarCorreoVerificacion($user);
                return redirect("/login")->with('success', 'Correo de verificación enviado. Por favor, revisa tu correo electrónico.');
            } else {
                return redirect()->back()->withErrors(['email' => 'Este correo electrónico ya está verificado.']);
            }
        }

        return redirect('/login')->withErrors(['email' => 'Correo electrónico no registrado.']);
    }
}
