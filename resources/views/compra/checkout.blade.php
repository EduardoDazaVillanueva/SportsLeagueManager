<x-layout :deportes="$deportes" :user="$user">

    <script src="https://js.stripe.com/v3/"></script>

    <div class="container">
        <h1 class="main_titulo">Comprar</h1>
        <div class="container_tienda">
            <div class="tienda_container-izq">
                <div class="tienda_info">
                    <h2 class="tienda_nombre"> {{ $producto->nombre }}</h2>
                    <p class="tienda_descripcion"> {{ $producto->descripcion }}</p>
                    <p class="tienda_precio"> {{ $producto->precio }}€</p>
                </div>
            </div>

            <div class="tienda_container-der">
                <form id="payment-form" action="{{ route('processPayment') }}" method="POST">
                    @csrf
                    <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                    <div class="form-group">
                        <label for="card-number" class="card-label">Número de Tarjeta</label>
                        <div id="card-number" class="card-input"></div>
                    </div>
                    <div class="form-group">
                        <label for="card-expiry" class="card-label">Fecha de Vencimiento</label>
                        <div id="card-expiry" class="card-input"></div>
                    </div>
                    <div class="form-group">
                        <label for="card-cvc" class="card-label">CVC</label>
                        <div id="card-cvc" class="card-input"></div>
                    </div>
                    <div class="form-group">
                        <label for="card-postal" class="card-label">Código Postal</label>
                        <div id="generated-postal" class="card-input"></div>
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

            // Crear elementos de tarjeta
            const cardNumber = elements.create('cardNumber');
            const cardExpiry = elements.create('cardExpiry');
            const cardCvc = elements.create('cardCvc');
            const postalCode = elements.create('postalCode');

            // Montar los elementos en el DOM
            cardNumber.mount('#card-number');
            cardExpiry.mount('#card-expiry');
            cardCvc.mount('#card-cvc');
            postalCode.mount('#generated-postal');

            // Manejar la acción del formulario
            const form = document.getElementById('payment-form');
            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const {
                    paymentMethod,
                    error
                } = await stripe.createPaymentMethod({
                    type: 'card',
                    card: cardNumber,
                    billing_details: {
                        // Aquí puedes agregar detalles de facturación si es necesario
                        name: 'Nombre del Cliente',
                        email: 'correo@ejemplo.com',
                        address: {
                            postal_code: document.getElementById('generated-postal').innerText,
                        },
                    },
                });

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
                            producto_id: document.querySelector(
                                'input[name="producto_id"]').value
                        })
                    });

                    const result = await response.json();

                    if (result.requires_action) {
                        stripe.handleCardAction(result.payment_intent_client_secret).then(
                        async function(result) {
                                if (result.error) {
                                    alert('El pago falló.');
                                } else {
                                    const confirmResponse = await fetch(
                                        "{{ route('processPayment') }}", {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: JSON.stringify({
                                                payment_intent_id: result
                                                    .paymentIntent.id,
                                                producto_id: document.querySelector(
                                                        'input[name="producto_id"]')
                                                    .value
                                            })
                                        });
                                    const confirmResult = await confirmResponse.json();
                                    handleServerResponse(confirmResult);
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
