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
        
        <!-- Visor PDF con iframe -->
        <div class="p-6 border-t border-gray-200">
            <h3 class="text-xl font-semibold mb-4">Lector de cómics</h3>
            
            <!-- Usando iframe para mostrar el PDF con el visor nativo del navegador -->
            <div class="bg-gray-100 p-4 rounded-lg mb-4">
                <div style="position: relative; padding-bottom: 100%; height: 0; overflow: hidden;">
                    <iframe src="{{ asset('storage/'.$comic->archivo_comic) }}" 
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" 
                            frameborder="0" 
                            allowfullscreen></iframe>
                </div>
            </div>
            
            <div class="text-center">
                <button id="actualizar-progreso" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
                    Guardar progreso de lectura
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnActualizar = document.getElementById('actualizar-progreso');
        
        btnActualizar.addEventListener('click', function() {
            // Esto es una simulación - en un caso real podríamos intentar 
            // obtener la página actual del PDF usando mensajes entre frames 
            // pero depende del navegador y de las políticas de seguridad
            const progresoEstimado = 50; // Estimamos 50% de progreso
            
            // Guardar progreso
            fetch('{{ route("biblioteca.actualizar", $comic->id_comic) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    progreso_lectura: progresoEstimado,
                    ultimo_marcador: 1 // Página estimada
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