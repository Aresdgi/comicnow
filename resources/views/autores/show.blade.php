@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Detalles del Autor</h1>
    
    <!-- Información del autor -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-2xl font-bold mb-4">Nombre del Autor</h2>
        <p>Biografía y detalles del autor se mostrarán aquí</p>
    </div>
    
    <!-- Cómics del autor -->
    <div class="mt-8">
        <h3 class="text-xl font-semibold mb-4">Cómics de este autor</h3>
        <p>Lista de cómics de este autor se mostrará aquí</p>
    </div>
</div>
@endsection