<x-layout :deportes="$deportes" :user="$user">

<script src="https://js.stripe.com/v3/"></script>

<div class="container">
    <h1>Checkout</h1>
    <div class="row">
        <div class="col-md-6">
            <h4>Resumen del Pedido</h4>
            <ul class="list-group">
                <!-- Añadir detalles del producto aquí -->
                <li class="list-group-item">Producto: {{ $producto->nombre }}</li>
                <li class="list-group-item">Precio: ${{ $producto->precio }}</li>
            </ul>
        </div>
        <div class="col-md-6">
            <form id="payment-form" action="{{ route('processPayment') }}" method="POST">
                @csrf
                <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                <div class="form-group">
                    <label for="card-element">Información de la Tarjeta</label>
                    <div id="card-element"></div>
                    <div id="card-errors" role="alert"></div>
                </div>
                <button type="submit" class="btn btn-primary">Pagar</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stripe = Stripe("{{ env('STRIPE_KEY') }}");
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        const form = document.getElementById('payment-form');
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const { paymentMethod, error } = await stripe.createPaymentMethod('card', cardElement);

            if (error) {
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
            } else {
                const response = await fetch("{{ route('processPayment') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        payment_method: paymentMethod.id,
                        producto_id: document.querySelector('input[name="producto_id"]').value
                    })
                });

                const result = await response.json();

                console.log(result);

                if (result.requires_action) {
                    stripe.handleCardAction(result.payment_intent_client_secret).then(function(result) {
                        if (result.error) {
                            alert('El pago falló.');
                        } else {
                            fetch("{{ route('processPayment') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    payment_intent_id: result.paymentIntent.id,
                                    producto_id: document.querySelector('input[name="producto_id"]').value
                                })
                            }).then(function(confirmResult) {
                                return confirmResult.json();
                            }).then(handleServerResponse);
                        }
                    });
                } else if (result.success) {
                    window.location.href = result.redirect_url;
                } else {
                    alert('El pago falló.');
                }
            }
        });

        function handleServerResponse(response) {
            if (response.error) {
                alert('El pago falló.');
            } else if (response.requires_action) {
                stripe.handleCardAction(response.payment_intent_client_secret).then(handleServerResponse);
            } else {
                window.location.href = response.redirect_url;
            }
        }
    });
</script>

</x-layout>
