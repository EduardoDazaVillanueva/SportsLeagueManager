<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Deportes;

class LoginController extends Controller
{
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
                'mimes:jpeg,jpg,png,gif',
                'max:2048'
            ],
            'telefono' => [
                'required',
                'string',
                'regex:/^\+?[\d\s()-]+$/',
                'min:9',
                'max:20'
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

        if (!array_key_exists('logo', $user)) {
            $user['logo'] = 'null';
        }

        unset($user['password_confirmation']);

        $user = User::create($user);

        $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice');
    }
    public function register()
    {
        return view('user.register', ['deportes' => Deportes::all()]);
    }

    public function getLogin()
    {
        return view('user.login', ['deportes' => Deportes::all()]);
    }

    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);


        if (auth()->attempt($credenciales)) {

            $request->session()->regenerate();

            return redirect('/');
        } else {
            return redirect()->back()->withErrors('message', 'Auth failed bad credentials');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'));
    }
}
