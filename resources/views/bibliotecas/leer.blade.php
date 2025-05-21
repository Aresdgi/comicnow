@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('biblioteca.index') }}" class="mr-4 text-gray-600 hover:text-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-3xl font-bold">{{ $comic->titulo }}</h1>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Detalles del cómic -->
        <div class="flex flex-col md:flex-row">
            <div class="w-full md:w-1/3 p-6">
                @if($comic->imagen)
                    <img src="{{ asset($comic->imagen) }}" alt="{{ $comic->titulo }}" class="w-full h-auto rounded-lg">
                @else
                    <div class="w-full h-96 bg-gray-200 flex items-center justify-center rounded-lg">
                        <span class="text-gray-500 text-lg">Sin imagen disponible</span>
                    </div>
                @endif
            </div>
            <div class="w-full md:w-2/3 p-6">
                <h2 class="text-2xl font-bold mb-4">{{ $comic->titulo }}</h2>
                
                @if($comic->autor)
                    <p class="text-gray-600 mb-2"><span class="font-semibold">Autor:</span> {{ $comic->autor->nombre }}</p>
                @endif
                
                <p class="text-gray-600 mb-2"><span class="font-semibold">Género:</span> {{ $comic->genero }}</p>
                <p class="text-gray-600 mb-2"><span class="font-semibold">Fecha de publicación:</span> {{ $comic->fecha_publicacion }}</p>
                
                <div class="mt-6">
                    <h3 class="text-lg font-semibold mb-2">Sinopsis</h3>
                    <p class="text-gray-700">{{ $comic->descripcion }}</p>
                </div>
                
                <!-- Progreso de lectura -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold mb-2">Tu progreso</h3>
                    <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
                        <div class="bg-blue-600 h-4 rounded-full" style="width: {{ $entrada->progreso_lectura }}%"></div>
                    </div>
                    <p class="text-sm text-gray-600">{{ $entrada->progreso_lectura }}% completado</p>
                </div>
            </div>
        </div>
        
        <!-- Área de visualización del cómic (placeholder para PDF.js) -->
        <div class="p-6 border-t border-gray-200">
            <h3 class="text-xl font-semibold mb-4">Vista previa del cómic</h3>
            
            <div class="bg-gray-100 p-8 rounded-lg text-center">
                <div class="bg-white p-8 rounded shadow-inner mx-auto max-w-3xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <h4 class="text-xl font-semibold mb-2">Visor de cómics en desarrollo</h4>
                    <p class="text-gray-600 mb-6">El visor de cómics estará disponible próximamente con PDF.js</p>
                    
                    <p class="text-gray-700 mb-4">Mientras tanto, puedes disfrutar de este cómic en formato digital.</p>
                    
                    <!-- Simulación de páginas del cómic -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        @for ($i = 1; $i <= 4; $i++)
                            <div class="bg-gray-200 rounded h-32 flex items-center justify-center">
                                <span class="text-gray-500">Página {{ $i }} (Demo)</span>
                            </div>
                        @endfor
                    </div>
                    
                    <button id="actualizar-progreso" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        Actualizar progreso al 25%
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnActualizar = document.getElementById('actualizar-progreso');
    
    btnActualizar.addEventListener('click', function() {
        // Simular actualización de progreso
        fetch('{{ route("biblioteca.actualizar", $comic->id_comic) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                progreso_lectura: 25,
                ultimo_marcador: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('¡Progreso actualizado!');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error al actualizar el progreso:', error);
        });
    });
});
</script>
@endsection 