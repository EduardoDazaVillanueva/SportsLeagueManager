<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Deportes;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function getWelcome(){
        return view('welcome', [
            'deportes' => Deportes::all()
        ]);
    }

    public function getCreate(){
        return view('liga.create', [
            'deportes' => Deportes::all()
        ]);
    }  
    
    public function getFAQ(){
        return view('faq', [
            'deportes' => Deportes::all()
        ]);
    }   
}
