<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Organizadores;
use Illuminate\Support\Facades\Auth;

class EresTu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->route('user');
        
        if ($user->id == Auth()->id()) {
            return $next($request);
        } else {
            return redirect()->back()->with('error', 'No puedes editar un perfil que no es tuyo');
        }
    }
}
