@extends('layouts.app')

@section('content')
<div class="container mx-auto px-2 sm:px-4 py-4 sm:py-8">
    <div class="flex items-center mb-4 sm:mb-6">
        <a href="{{ route('biblioteca.index') }}" class="mr-2 sm:mr-4 text-gray-600 hover:text-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold truncate">{{ $comic->titulo }}</h1>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Detalles del cómic -->
        <div class="flex flex-col md:flex-row">
            <div class="w-full md:w-1/3 p-4 sm:p-6">
                @if($comic->portada_url)
                    <img src="{{ asset('storage/' . $comic->portada_url) }}" alt="{{ $comic->titulo }}" class="w-full h-auto rounded-lg">
                @else
                    <div class="w-full h-60 sm:h-96 bg-gray-200 flex items-center justify-center rounded-lg">
                        <span class="text-gray-500 text-lg">Sin imagen disponible</span>
                    </div>
                @endif
            </div>
            <div class="w-full md:w-2/3 p-4 sm:p-6">
                <h2 class="text-xl sm:text-2xl font-bold mb-3 sm:mb-4">{{ $comic->titulo }}</h2>
                
                @if($comic->autor)
                    <p class="text-gray-600 mb-2"><span class="font-semibold">Autor:</span> {{ $comic->autor->nombre }}</p>
                @endif
                
                <p class="text-gray-600 mb-2"><span class="font-semibold">Género:</span> {{ $comic->genero }}</p>
                <p class="text-gray-600 mb-2"><span class="font-semibold">Fecha de publicación:</span> {{ $comic->fecha_publicacion }}</p>
                
                <div class="mt-4 sm:mt-6">
                    <h3 class="text-base sm:text-lg font-semibold mb-2">Sinopsis</h3>
                    <p class="text-gray-700 text-sm sm:text-base">{{ $comic->descripcion }}</p>
                </div>
                
                <!-- Progreso de lectura -->
                <div class="mt-4 sm:mt-6">
                    <h3 class="text-base sm:text-lg font-semibold mb-2">Tu progreso</h3>
                    <div class="w-full bg-gray-200 rounded-full h-3 sm:h-4 mb-2">
                        @php
                            $progreso = $entrada->progreso_lectura ?? 0;
                        @endphp
                        <div class="bg-blue-600 h-3 sm:h-4 rounded-full" style="width: {{ $progreso }}%"></div>
                    </div>
                    <p class="text-xs sm:text-sm text-gray-600">{{ $progreso }}% completado</p>
                </div>
            </div>
        </div>
        
        <!-- Visor PDF con iframe -->
        <div class="p-4 sm:p-6 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-4 gap-3 sm:gap-0">
                <h3 class="text-lg sm:text-xl font-semibold">Lector de cómics</h3>
                <button id="fullscreen-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-1.5 sm:py-2 text-sm sm:text-base rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mr-1 sm:mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 0h-4m4 0l-5-5" />
                    </svg>
                    Ver a pantalla completa
                </button>
            </div>
            
            <!-- Usando iframe para mostrar el PDF con el visor nativo del navegador -->
            <div class="bg-gray-100 p-2 sm:p-4 rounded-lg mb-4">
                <div id="pdf-container" style="position: relative; padding-bottom: 140%; height: 0; overflow: hidden;">
                    <iframe id="pdf-iframe" src="{{ asset('storage/' . $comic->archivo_comic) }}" 
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" 
                            frameborder="0" 
                            allowfullscreen></iframe>
                </div>
            </div>
            
            <div class="text-center">
                <button id="actualizar-progreso" class="bg-indigo-600 text-white w-full sm:w-auto px-4 sm:px-6 py-2 rounded hover:bg-indigo-700 text-sm sm:text-base">
                    Guardar progreso de lectura
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar si el iframe ha cargado correctamente
        const pdfIframe = document.getElementById('pdf-iframe');
        pdfIframe.onload = function() {
            console.log('PDF cargado correctamente');
        };
        pdfIframe.onerror = function() {
            console.error('Error al cargar el PDF');
            // Mostrar un mensaje de error
            const pdfContainer = document.getElementById('pdf-container');
            pdfContainer.innerHTML = `
                <div class="bg-red-100 border border-red-400 text-red-700 p-4 rounded flex flex-col items-center justify-center h-full">
                    <p class="font-bold mb-2">Error al cargar el cómic</p>
                    <p>No se ha podido cargar el archivo PDF. Por favor contacte con el administrador.</p>
                    <p class="text-sm mt-2">Ruta: ${pdfIframe.src}</p>
                </div>
            `;
        };
        
        // Ajustar el contenedor de PDF según el tamaño de la pantalla
        function adjustPdfContainer() {
            const pdfContainer = document.getElementById('pdf-container');
            if (window.innerWidth >= 1024) {
                // Escritorio grande
                pdfContainer.style.paddingBottom = '75%';
            } else if (window.innerWidth >= 640) {
                // Tablet
                pdfContainer.style.paddingBottom = '100%';
            } else {
                // Móvil
                pdfContainer.style.paddingBottom = '140%';
            }
        }
        
        // Ajustar al cargar y al cambiar el tamaño de la ventana
        adjustPdfContainer();
        window.addEventListener('resize', adjustPdfContainer);
        
        // Botón de pantalla completa
        const fullscreenBtn = document.getElementById('fullscreen-btn');
        fullscreenBtn.addEventListener('click', function() {
            // Abrir el PDF en una nueva ventana a pantalla completa
            window.open("{{ asset('storage/' . $comic->archivo_comic) }}", "_blank", "fullscreen=yes,toolbar=yes");
        });

        // Botón para actualizar progreso
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