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
        <div class="flex flex-row max-sm:flex-col gap-4">
            <div class="flex-shrink-0" style="max-width: 250px;">
                @if($comic->portada_url)
                    <img src="{{ asset('storage/' . $comic->portada_url) }}" alt="{{ $comic->titulo }}" class="w-full h-auto rounded-lg" style="max-width: 250px;">
                @else
                    <div class="w-full bg-gray-200 flex items-center justify-center rounded-lg" style="max-width: 250px; height: 300px;">
                        <span class="text-gray-500 text-sm">Sin imagen disponible</span>
                    </div>
                @endif
            </div>
            <div class="flex-1 p-2 sm:p-4 lg:p-6">
                <h2 class="text-lg sm:text-xl lg:text-2xl font-bold mb-2 sm:mb-3 lg:mb-4">{{ $comic->titulo }}</h2>
                
                @if($comic->autor)
                    <p class="text-gray-600 mb-1 sm:mb-2 text-sm sm:text-base"><span class="font-semibold">Autor:</span> {{ $comic->autor->nombre }}</p>
                @endif
                
                <p class="text-gray-600 mb-1 sm:mb-2 text-sm sm:text-base"><span class="font-semibold">Categoría:</span> {{ $comic->categoria }}</p>
                <p class="text-gray-600 mb-2 sm:mb-2 text-sm sm:text-base"><span class="font-semibold">Fecha de publicación:</span> {{ $comic->fecha_publicacion }}</p>
                
                <div class="mt-3 sm:mt-4 lg:mt-6">
                    <h3 class="text-sm sm:text-base lg:text-lg font-semibold mb-1 sm:mb-2">Sinopsis</h3>
                    <p class="text-gray-700 text-xs sm:text-sm lg:text-base">{{ $comic->descripcion }}</p>
                </div>
                
                <!-- Sección para escribir reseña -->
                <div class="mt-4 sm:mt-6 lg:mt-8">
                    <h3 class="text-sm sm:text-base lg:text-lg font-semibold mb-3 sm:mb-4">Tu reseña</h3>
                    
                    @if(!$resenaUsuario)
                        <div class="bg-gray-50 p-3 sm:p-4 rounded-lg">
                            <h4 class="text-sm sm:text-base font-medium mb-2 sm:mb-3">Escribe tu reseña sobre este cómic</h4>
                            
                            @if(session('success'))
                                <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded mb-3 text-sm">
                                    {{ session('success') }}
                                </div>
                            @endif
                            
                            @if(session('error'))
                                <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded mb-3 text-sm">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form action="{{ route('resenas.store.reader', $comic->id_comic) }}" method="POST">
                                @csrf
                                
                                <!-- Valoración -->
                                <div class="mb-3">
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Valoración</label>
                                    <div class="flex space-x-1" id="star-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button type="button" class="star-btn text-gray-300 hover:text-yellow-500 transition-colors" data-rating="{{ $i }}">
                                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </button>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="valoracion" id="valoracion" required>
                                    @if($errors->has('valoracion'))
                                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('valoracion') }}</p>
                                    @endif
                                </div>

                                <!-- Comentario -->
                                <div class="mb-3">
                                    <label for="comentario" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Comentario</label>
                                    <textarea name="comentario" id="comentario" rows="3" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none" 
                                            placeholder="Comparte tu opinión sobre este cómic..." 
                                            required>{{ old('comentario') }}</textarea>
                                    @if($errors->has('comentario'))
                                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('comentario') }}</p>
                                    @endif
                                </div>

                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition">
                                    Publicar reseña
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="bg-blue-50 p-3 sm:p-4 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs sm:text-sm text-blue-800 font-medium">Ya has reseñado este cómic</p>
                                    <p class="text-xs text-blue-600 mt-1">Tu valoración: 
                                        <span class="inline-flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-3 h-3 {{ $i <= $resenaUsuario->valoracion ? 'text-yellow-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </span>
                                    </p>
                                </div>
                                <a href="{{ route('user.resenas') }}" class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm underline">
                                    Gestionar mis reseñas
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Sección para ver reseñas de otros usuarios -->
                <div class="mt-4 sm:mt-6 lg:mt-8">
                    <div class="flex justify-between items-center mb-3 sm:mb-4">
                        <h3 class="text-sm sm:text-base lg:text-lg font-semibold">Reseñas de la comunidad</h3>
                        @if($totalResenas > 0)
                            <div class="flex items-center text-xs sm:text-sm text-gray-600">
                                <div class="flex items-center mr-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 {{ $i <= round($promedioValoracion) ? 'text-yellow-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span>{{ number_format($promedioValoracion, 1) }} ({{ $totalResenas }} {{ $totalResenas == 1 ? 'reseña' : 'reseñas' }})</span>
                            </div>
                        @endif
                    </div>

                    <!-- Lista de reseñas existentes -->
                    @if($comic->resenas && $comic->resenas->count() > 0)
                        <div class="space-y-3 sm:space-y-4">
                            @foreach($comic->resenas->take(3) as $resena)
                                <div class="bg-white border border-gray-200 p-3 sm:p-4 rounded-lg">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex items-center">
                                            <span class="font-medium text-xs sm:text-sm text-gray-900">{{ $resena->usuario->nombre ?? 'Usuario anónimo' }}</span>
                                            <div class="flex items-center ml-2 sm:ml-3">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 {{ $i <= $resena->valoracion ? 'text-yellow-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($resena->fecha)->format('d/m/Y') }}</span>
                                    </div>
                                    <p class="text-gray-700 text-xs sm:text-sm">{{ $resena->comentario }}</p>
                                </div>
                            @endforeach
                            
                            @if($comic->resenas->count() > 3)
                                <div class="text-center">
                                    <button onclick="toggleMoreReviews()" id="toggle-reviews-btn" class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium">
                                        Ver más reseñas ({{ $comic->resenas->count() - 3 }} restantes)
                                    </button>
                                </div>
                                
                                <div id="more-reviews" class="hidden space-y-3 sm:space-y-4">
                                    @foreach($comic->resenas->skip(3) as $resena)
                                        <div class="bg-white border border-gray-200 p-3 sm:p-4 rounded-lg">
                                            <div class="flex justify-between items-start mb-2">
                                                <div class="flex items-center">
                                                    <span class="font-medium text-xs sm:text-sm text-gray-900">{{ $resena->usuario->nombre ?? 'Usuario anónimo' }}</span>
                                                    <div class="flex items-center ml-2 sm:ml-3">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 {{ $i <= $resena->valoracion ? 'text-yellow-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                            </svg>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($resena->fecha)->format('d/m/Y') }}</span>
                                            </div>
                                            <p class="text-gray-700 text-xs sm:text-sm">{{ $resena->comentario }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-4 sm:py-6 text-gray-500">
                            <p class="text-xs sm:text-sm">Este cómic aún no tiene reseñas de otros usuarios.</p>
                        </div>
                    @endif
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
            <div class="bg-gray-100 p-2 sm:p-4 rounded-lg">
                <div id="pdf-container" style="position: relative; padding-bottom: 140%; height: 0; overflow: hidden;">
                    <iframe id="pdf-iframe" src="{{ asset('storage/' . $comic->archivo_comic) }}" 
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" 
                            frameborder="0" 
                            allowfullscreen></iframe>
                </div>
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
        
        // Sistema de estrellas para valoración
        const starButtons = document.querySelectorAll('.star-btn');
        const valoracionInput = document.getElementById('valoracion');
        let selectedRating = 0;
        
        starButtons.forEach((star, index) => {
            star.addEventListener('click', function() {
                selectedRating = index + 1;
                valoracionInput.value = selectedRating;
                updateStars(selectedRating);
            });
            
            star.addEventListener('mouseenter', function() {
                updateStars(index + 1);
            });
        });
        
        // Restaurar estrellas al salir del hover si no hay selección
        const starRating = document.getElementById('star-rating');
        if (starRating) {
            starRating.addEventListener('mouseleave', function() {
                updateStars(selectedRating);
            });
        }
        
        function updateStars(rating) {
            starButtons.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-500');
                } else {
                    star.classList.remove('text-yellow-500');
                    star.classList.add('text-gray-300');
                }
            });
        }
    });
    
    // Función para mostrar/ocultar más reseñas
    function toggleMoreReviews() {
        const moreReviews = document.getElementById('more-reviews');
        const toggleBtn = document.getElementById('toggle-reviews-btn');
        
        if (moreReviews.classList.contains('hidden')) {
            moreReviews.classList.remove('hidden');
            toggleBtn.textContent = 'Ver menos reseñas';
        } else {
            moreReviews.classList.add('hidden');
            toggleBtn.textContent = 'Ver más reseñas ({{ $comic->resenas->count() - 3 }} restantes)';
        }
    }
</script>
@endsection 