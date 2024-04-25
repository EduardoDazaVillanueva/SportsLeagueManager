<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Deportes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViewController extends Controller
{
    public function getWelcome(){
        return view('welcome', [
            'deportes' => Deportes::all(),
            'user' => Auth::user(),
        ]);
    }

    public function getCreate(){
        return view('liga.create', [
            'deportes' => Deportes::all(),
            'user' => Auth::user(),
        ]);
    }  
    
    public function getFAQ(){
        return view('faq', [
            'deportes' => Deportes::all(),
            'user' => Auth::user(),
        ]);
    }   
}
