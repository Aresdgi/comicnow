@extends('layouts.app')

@section('content')
<div class="container mx-auto px-2 sm:px-4 py-4 sm:py-8">
    <div class="flex items-center mb-4 sm:mb-6">
        <a href="{{ route('biblioteca.index') }}" class="mr-2 sm:mr-4 text-gray-600 hover:text-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold truncate">{{ $comic->titulo }}</h1>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Detalles del cómic -->
        <div class="flex flex-col lg:flex-row gap-4 sm:gap-6 lg:gap-8 p-3 sm:p-4 lg:p-8">
            <div class="flex-shrink-0 mx-auto lg:mx-0">
                @if($comic->portada_url)
                    <img src="{{ asset('storage/' . $comic->portada_url) }}" alt="{{ $comic->titulo }}" 
                         class="w-48 sm:w-60 lg:w-80 xl:w-96 h-auto rounded-lg shadow-lg">
                @else
                    <div class="w-48 sm:w-60 lg:w-80 xl:w-96 h-72 lg:h-96 xl:h-[500px] bg-gray-200 flex items-center justify-center rounded-lg shadow-lg">
                        <span class="text-gray-500 text-sm lg:text-base">Sin imagen disponible</span>
                    </div>
                @endif
            </div>
            <div class="flex-1 lg:pl-4">
                <h2 class="text-lg sm:text-xl lg:text-2xl xl:text-3xl font-bold mb-3 sm:mb-4 lg:mb-6">{{ $comic->titulo }}</h2>
                
                <div class="space-y-3 lg:space-y-4 mb-4 lg:mb-6">
                    @if($comic->autor)
                        <p class="text-gray-600 text-sm sm:text-base lg:text-lg"><span class="font-semibold">Autor:</span> {{ $comic->autor->nombre }}</p>
                    @endif
                    
                    <p class="text-gray-600 text-sm sm:text-base lg:text-lg"><span class="font-semibold">Categoría:</span> {{ $comic->categoria }}</p>
                    <p class="text-gray-600 text-sm sm:text-base lg:text-lg"><span class="font-semibold">Fecha de publicación:</span> {{ $comic->fecha_publicacion }}</p>
                </div>
                
                <div class="mb-4 sm:mb-6 lg:mb-8">
                    <h3 class="text-base sm:text-lg lg:text-xl font-semibold mb-2 lg:mb-3">Sinopsis</h3>
                    <p class="text-gray-700 text-sm sm:text-base lg:text-lg leading-relaxed">{{ $comic->descripcion }}</p>
                </div>
                
                <!-- Sección para escribir reseña -->
                <div class="mb-4 sm:mb-6 lg:mb-8">
                    <h3 class="text-base sm:text-lg lg:text-xl font-semibold mb-3 lg:mb-4">Tu reseña</h3>
                    
                    @if(!$resenaUsuario)
                        <div class="bg-gray-50 p-3 sm:p-4 lg:p-6 rounded-lg">
                            <h4 class="text-sm sm:text-base lg:text-lg font-medium mb-3 lg:mb-4">Escribe tu reseña sobre este cómic</h4>
                            
                            @if(session('success'))
                                <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded mb-3 text-sm lg:text-base">
                                    {{ session('success') }}
                                </div>
                            @endif
                            
                            @if(session('error'))
                                <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded mb-3 text-sm lg:text-base">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form action="{{ route('resenas.store.reader', $comic->id_comic) }}" method="POST" class="space-y-3 lg:space-y-4">
                                @csrf
                                
                                <!-- Valoración -->
                                <div>
                                    <label class="block text-sm lg:text-base font-medium text-gray-700 mb-2 lg:mb-3">Valoración</label>
                                    <div class="flex space-x-1 lg:space-x-2" id="star-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button type="button" class="star-btn text-gray-300 hover:text-yellow-500 transition-colors p-1" data-rating="{{ $i }}">
                                                <svg class="w-6 h-6 lg:w-8 lg:h-8" fill="currentColor" viewBox="0 0 20 20">
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
                                <div>
                                    <label for="comentario" class="block text-sm lg:text-base font-medium text-gray-700 mb-2 lg:mb-3">Comentario</label>
                                    <textarea name="comentario" id="comentario" rows="4" 
                                            class="w-full px-3 py-2 lg:px-4 lg:py-3 border border-gray-300 rounded-md text-sm lg:text-base focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none" 
                                            placeholder="Comparte tu opinión sobre este cómic..." 
                                            required>{{ old('comentario') }}</textarea>
                                    @if($errors->has('comentario'))
                                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('comentario') }}</p>
                                    @endif
                                </div>

                                <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 lg:px-6 py-2 lg:py-3 rounded-lg text-sm lg:text-base font-medium transition">
                                    Publicar reseña
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="bg-blue-50 p-3 sm:p-4 lg:p-6 rounded-lg">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-2 lg:gap-4">
                                <div>
                                    <p class="text-sm lg:text-base text-blue-800 font-medium">Ya has reseñado este cómic</p>
                                    <p class="text-xs lg:text-sm text-blue-600 mt-1 flex items-center gap-1">
                                        <span>Tu valoración:</span>
                                        <span class="inline-flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-3 h-3 lg:w-4 lg:h-4 {{ $i <= $resenaUsuario->valoracion ? 'text-yellow-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </span>
                                    </p>
                                </div>
                                <a href="{{ route('user.resenas') }}" class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm lg:text-base underline text-center">
                                    Gestionar mis reseñas
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Sección para ver reseñas de otros usuarios -->
                <div>
                    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mb-3 lg:mb-4 gap-2">
                        <h3 class="text-base sm:text-lg lg:text-xl font-semibold">Reseñas de la comunidad</h3>
                        @if($totalResenas > 0)
                            <div class="flex items-center text-sm lg:text-base text-gray-600">
                                <div class="flex items-center mr-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 lg:w-5 lg:h-5 {{ $i <= round($promedioValoracion) ? 'text-yellow-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-xs sm:text-sm lg:text-base">{{ number_format($promedioValoracion, 1) }} ({{ $totalResenas }} {{ $totalResenas == 1 ? 'reseña' : 'reseñas' }})</span>
                            </div>
                        @endif
                    </div>

                    <!-- Lista de reseñas existentes -->
                    @if($comic->resenas && $comic->resenas->count() > 0)
                        <div class="space-y-3 lg:space-y-4">
                            @foreach($comic->resenas->take(3) as $resena)
                                <div class="bg-white border border-gray-200 p-3 lg:p-4 rounded-lg">
                                    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start mb-2 gap-1">
                                        <div class="flex items-center">
                                            <span class="font-medium text-sm lg:text-base text-gray-900">{{ $resena->usuario->nombre ?? 'Usuario anónimo' }}</span>
                                            <div class="flex items-center ml-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-3 h-3 lg:w-4 lg:h-4 {{ $i <= $resena->valoracion ? 'text-yellow-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        <span class="text-xs lg:text-sm text-gray-500">{{ \Carbon\Carbon::parse($resena->fecha)->format('d/m/Y') }}</span>
                                    </div>
                                    <p class="text-gray-700 text-sm lg:text-base leading-relaxed">{{ $resena->comentario }}</p>
                                </div>
                            @endforeach
                            
                            @if($comic->resenas->count() > 3)
                                <div class="text-center">
                                    <button onclick="toggleMoreReviews()" id="toggle-reviews-btn" class="text-blue-600 hover:text-blue-800 text-sm lg:text-base font-medium underline">
                                        Ver más reseñas ({{ $comic->resenas->count() - 3 }} restantes)
                                    </button>
                                </div>
                                
                                <div id="more-reviews" class="hidden space-y-3 lg:space-y-4">
                                    @foreach($comic->resenas->skip(3) as $resena)
                                        <div class="bg-white border border-gray-200 p-3 lg:p-4 rounded-lg">
                                            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start mb-2 gap-1">
                                                <div class="flex items-center">
                                                    <span class="font-medium text-sm lg:text-base text-gray-900">{{ $resena->usuario->nombre ?? 'Usuario anónimo' }}</span>
                                                    <div class="flex items-center ml-2">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <svg class="w-3 h-3 lg:w-4 lg:h-4 {{ $i <= $resena->valoracion ? 'text-yellow-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                            </svg>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <span class="text-xs lg:text-sm text-gray-500">{{ \Carbon\Carbon::parse($resena->fecha)->format('d/m/Y') }}</span>
                                            </div>
                                            <p class="text-gray-700 text-sm lg:text-base leading-relaxed">{{ $resena->comentario }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-6 lg:py-8 text-gray-500 bg-gray-50 rounded-lg">
                            <p class="text-sm lg:text-base">Este cómic aún no tiene reseñas de otros usuarios.</p>
                            <p class="text-xs lg:text-sm mt-1">¡Sé el primero en escribir una!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Visor PDF con iframe -->
        <div class="p-3 sm:p-4 lg:p-6 xl:p-8 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-4 lg:mb-6 gap-3">
                <h3 class="text-lg sm:text-xl lg:text-2xl font-semibold">Lector de cómics</h3>
                <button id="fullscreen-btn" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 lg:px-6 py-2 lg:py-3 text-sm lg:text-base font-medium rounded-lg flex items-center justify-center transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 lg:h-5 lg:w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 0h-4m4 0l-5-5" />
                    </svg>
                    Ver a pantalla completa
                </button>
            </div>
            
            <!-- Usando iframe para mostrar el PDF con el visor nativo del navegador -->
            <div class="bg-gray-100 p-2 sm:p-4 lg:p-6 rounded-lg">
                <div id="pdf-container" class="relative overflow-hidden rounded" style="padding-bottom: 150%; height: 0;">
                    <iframe id="pdf-iframe" src="{{ asset('storage/' . $comic->archivo_comic) }}" 
                            class="absolute top-0 left-0 w-full h-full border-0 rounded" 
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
                    <p class="text-center">No se ha podido cargar el archivo PDF. Por favor contacte con el administrador.</p>
                    <p class="text-sm mt-2 text-center break-all">Ruta: ${pdfIframe.src}</p>
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
                pdfContainer.style.paddingBottom = '150%';
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