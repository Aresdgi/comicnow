@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Carrito de Compras</h1>
    
    <!-- Artículos en el carrito -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Artículos</h2>
        <p>Los cómics en tu carrito se mostrarán aquí</p>
    </div>
    
    <!-- Resumen de la compra -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-xl font-semibold mb-4">Resumen</h3>
        <p>El resumen de tu compra se mostrará aquí</p>
        
        <!-- Botón de checkout -->
        <div class="mt-6">
            <p>Botón para finalizar la compra irá aquí</p>
        </div>
    </div>
</div>
@endsection