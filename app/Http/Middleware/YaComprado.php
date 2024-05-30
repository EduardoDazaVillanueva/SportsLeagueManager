<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UsuarioCompraProducto;

use Carbon\Carbon;

class YaComprado
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $comprado = UsuarioCompraProducto::where('user_id', Auth()->id())
            ->where('producto_id', $request->route('producto')->id)
            ->first();

        $suscripcion = UsuarioCompraProducto::where('user_id', Auth()->id())
            ->whereIn('producto_id', [1, 2, 3, 4])
            ->orderBy('created_at', 'desc')
            ->first();


        if (in_array($request->route('producto')->id, [1, 2, 3, 4])) {
            if ($suscripcion) {
                if ($suscripcion->producto_id == 1) {
                    $diasRestantes = $this->comprobarSuscripcion($suscripcion, 30);

                    if ($diasRestantes > 0) {
                        return redirect()->back()->with('error', 'No puedes comprar este producto');
                    } else {
                        return $next($request);
                    }
                } elseif ($suscripcion->producto_id == 2) {
                    $diasRestantes = $this->comprobarSuscripcion($suscripcion, 90);

                    if ($diasRestantes > 0) {
                        return redirect()->back()->with('error', 'No puedes comprar este producto');
                    } else {
                        return $next($request);
                    }
                } elseif ($suscripcion->producto_id == 3) {
                    $diasRestantes = $this->comprobarSuscripcion($suscripcion, 365);

                    if ($diasRestantes > 0) {
                        return redirect()->back()->with('error', 'No puedes comprar este producto');
                    } else {
                        return $next($request);
                    }
                } else {
                    return redirect()->back()->with('error', 'No puedes comprar este producto');
                }
            } else {
                return $next($request);
            }
        } else {
            if ($comprado) {
                return redirect()->back()->with('error', 'No puedes comprar este producto');
            } else {
                return $next($request);
            }
        }
    }

    private function comprobarSuscripcion($suscripcion, $dias)
    {
        $fechaCreacion = Carbon::parse($suscripcion->created_at);
        $fechaExpiracion = $fechaCreacion->addDays($dias);
        $fechaActual = Carbon::now();

        if ($fechaActual->greaterThanOrEqualTo($fechaExpiracion)) {
            // La suscripción ha expirado
            return -1;
        } else {
            // La suscripción aún es válida, devolver cuántos días quedan
            return floor($fechaActual->diffInDays($fechaExpiracion));
        }
    }
}
