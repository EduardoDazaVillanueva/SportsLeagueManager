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
    public function store(Request $request){
        $user = $request->validate([
            'name' => ['required', 'string', Rule::unique('users', 'name')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'logo' => ['required'],
            'telefono' => ['required'],
            'password' => ['required', 'min:8']
        ]);
        
        $user = User::create($user);
        auth()->login($user);

        return redirect(route('dashboard'));
    }
    public function register(){
        return view('user.register',['deportes' => Deportes::all()] );
    }

    public function getLogin(){
        return view('user.login',['deportes' => Deportes::all()] );
    }

    public function login(Request $request){
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);


        if(auth()->attempt($credenciales)){

            $request->session()->regenerate();

            return redirect('/');
        }else{
            return redirect()->back()->withErrors('message' , 'Auth failed bad credentials');
        }
    }

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'));
    }
}
