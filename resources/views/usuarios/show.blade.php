@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Perfil de Usuario</h1>
    
    <!-- Información del usuario -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Información Personal</h2>
        <p>Los datos del usuario se mostrarán aquí</p>
    </div>
    
    <!-- Actividad del usuario -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-xl font-semibold mb-4">Actividad Reciente</h3>
        <p>Actividad reciente del usuario se mostrará aquí</p>
    </div>
</div>
@endsection