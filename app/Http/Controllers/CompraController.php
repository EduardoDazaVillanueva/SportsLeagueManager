<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Productos;
use App\Models\Deportes;
use App\Models\Jugadores;
use App\Models\Organizadores;
use App\Models\ParticipaEnLiga;
use App\Models\UsuarioCompraProducto;
use Illuminate\Support\Facades\Auth;

class CompraController extends Controller
{
    public function checkout(Request $request)
    {
        $producto = Productos::findOrFail($request->producto_id);

        return view('compra.checkout', [
            'deportes' => Deportes::all(),
            'user' => Auth::user(),
            'producto' => $producto
        ]);
    }

    public function processPayment(Request $request)
    {
        // Establecer la clave secreta de Stripe
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Obtener el usuario autenticado
        $user = Auth::user();

        // Validar la entrada del producto
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'payment_method' => 'required|string'
        ]);

        // Obtener el producto
        $producto = Productos::find($request->producto_id);

        // Crear un PaymentIntent con Stripe
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $producto->precio * 100,
                'currency' => 'eur',
                'payment_method' => $request->payment_method,
                'confirmation_method' => 'manual',
                'confirm' => true,
                'return_url' => route('paymentCallback', ['producto_id' => $producto->id]),
            ]);

            if ($paymentIntent->status == 'requires_action' && $paymentIntent->next_action->type == 'use_stripe_sdk') {
                return response()->json(['requires_action' => true, 'payment_intent_client_secret' => $paymentIntent->client_secret]);
            } elseif ($paymentIntent->status == 'succeeded') {
                // Guardar la compra en la base de datos
                UsuarioCompraProducto::create([
                    'fecha_compra' => now(),
                    'producto_id' => $producto->id,
                    'user_id' => $user->id,
                ]);

                return response()->json(['success' => true, 'redirect_url' => route('paymentCallback', ['producto_id' => $producto->id])]);
            } else {
                return response()->json(['error' => 'Invalid PaymentIntent status']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function paymentCallback(Request $request)
    {
        $producto_id = $request->route('producto_id');
        $producto = Productos::findOrFail($producto_id);
        $user = Auth::user();
        $liga_id = $producto->liga_id;
        
        // Verificar si el usuario es un organizador y crear uno si no lo es
        
        if (in_array($producto->id, [1, 2, 3, 4])) {
            Organizadores::firstOrCreate(['user_id' => $user->id]);
        }else{
            $jugador = Jugadores::firstOrCreate(['user_id' => $user->id]);
            ParticipaEnLiga::firstOrCreate(['liga_id' => $liga_id,
            'jugadores_id' => $jugador->id,]);
        }

        return view('welcome')->with([
            'success' => '¡Pago realizado con éxito para el producto: ' . $producto->nombre,
            'deportes' => Deportes::all(),
            'user' => $user,
            'productos' => Productos::all()
        ]);
    }
}
