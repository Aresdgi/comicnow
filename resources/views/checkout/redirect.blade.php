@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Redirigiendo a Stripe Checkout</div>

                <div class="card-body text-center">
                    <h2>Estamos preparando tu pago...</h2>
                    <p>Serás redirigido a Stripe Checkout en unos segundos para completar tu compra.</p>
                    <div class="spinner-border mt-3" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Redireccionar automáticamente a Stripe Checkout
    window.onload = function() {
        window.location.href = "{{ $checkout_url }}";
    }
</script>
@endsection 