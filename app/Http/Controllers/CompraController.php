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
    public function checkout(Request $request, Productos $producto)
    {
        return view('compra.checkout', [
            'deportes' => Deportes::all(),
            'user' => Auth::user(),
            'producto' => $producto
        ]);
    }

    public function processPayment(Request $request)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $request->validate([
                'producto_id' => 'required|exists:productos,id',
                'payment_method' => 'required|string'
            ]);

            $user = Auth::user();

            $producto = Productos::findOrFail($request->producto_id);

            // Crear un PaymentIntent con Stripe
            $paymentIntent = PaymentIntent::create([
                'amount' => $producto->precio * 100,
                'currency' => 'eur',
                'payment_method' => $request->payment_method,
                'confirmation_method' => 'manual',
                'description' => $producto->nombre,
                'confirm' => true,
                'return_url' => route('paymentCallback', ['producto' => $producto->id]),
            ]);

            // Manejar diferentes estados de PaymentIntent
            if ($paymentIntent->status == 'requires_action' && $paymentIntent->next_action->type == 'use_stripe_sdk') {
                return response()->json(['requires_action' => true, 'payment_intent_client_secret' => $paymentIntent->client_secret]);
            } elseif ($paymentIntent->status == 'succeeded') {
                // Guardar la compra en la base de datos
                UsuarioCompraProducto::create([
                    'fecha_compra' => now(),
                    'producto_id' => $producto->id,
                    'user_id' => $user->id,
                ]);

                return response()->json(['success' => true, 'redirect_url' => route('paymentCallback', ['producto' => $producto->id])]);
            } else {
                return response()->json(['error' => 'Invalid PaymentIntent status']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }


    public function paymentCallback(Request $request, Productos $producto)
    {
        $user = Auth::user();
        $liga_id = $producto->liga_id;

        // Verificar si el usuario es un organizador y crear uno si no lo es
        if (in_array($producto->id, [1, 2, 3, 4])) {
            Organizadores::firstOrCreate(['user_id' => $user->id]);

            return view('welcome')->with([
                'success' => '¡Pago realizado con éxito para el producto: ' . $producto->nombre,
                'deportes' => Deportes::all(),
                'user' => $user,
                'productos' => Productos::all()
            ]);
        } else {
            $jugador = Jugadores::firstOrCreate(['user_id' => $user->id]);
            ParticipaEnLiga::firstOrCreate([
                'liga_id' => $liga_id,
                'jugadores_id' => $jugador->id,
            ]);

            return redirect()->route('liga.show', ['liga' => $liga_id])->with([
                'success' => '¡Pago realizado con éxito para el producto: ' . $producto->nombre,
                'deportes' => Deportes::all(),
                'user' => $user,
                'liga' => $liga_id
            ]);
        }
    }
}
