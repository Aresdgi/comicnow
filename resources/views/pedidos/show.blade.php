@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Detalles del Pedido</h1>
    
    <!-- Información del pedido -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Información General</h2>
        <p>Detalles generales del pedido se mostrarán aquí</p>
    </div>
    
    <!-- Detalles del pedido -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-xl font-semibold mb-4">Artículos</h3>
        <p>Los artículos del pedido se mostrarán aquí</p>
    </div>
</div>
@endsection