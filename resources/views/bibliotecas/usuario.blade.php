@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Biblioteca de Usuario</h1>
    
    <!-- Información del usuario -->
    <div class="bg-white shadow-md rounded-lg p-4 mb-6">
        <h2 class="text-xl font-semibold">Nombre del Usuario</h2>
    </div>
    
    <!-- Cómics en la biblioteca del usuario -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <!-- Placeholder para cómics en la biblioteca -->
        <p>Los cómics del usuario se mostrarán aquí</p>
    </div>
</div>
@endsection