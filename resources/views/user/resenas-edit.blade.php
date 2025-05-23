<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        <a href="{{ route('user.resenas') }}" class="mr-4 text-gray-600 hover:text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>
                        <h1 class="text-2xl font-bold">Editar Reseña</h1>
                    </div>
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Información del cómic -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-4">
                                @if($resena->comic->portada_url)
                                    <img src="{{ asset('storage/' . $resena->comic->portada_url) }}" alt="{{ $resena->comic->titulo }}" class="w-20 h-28 object-cover rounded">
                                @else
                                    <div class="w-20 h-28 bg-gray-200 rounded flex items-center justify-center">
                                        <span class="text-gray-500 text-xs">Sin imagen</span>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold">{{ $resena->comic->titulo }}</h2>
                                <p class="text-sm text-gray-600">{{ $resena->comic->autor->nombre ?? 'Autor desconocido' }}</p>
                                <p class="text-xs text-gray-500 mt-1">Reseña creada: {{ $resena->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario de edición -->
                    <form action="{{ route('user.resenas.update', $resena->id_resena) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Valoración -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Valoración</label>
                            <div class="flex space-x-1" id="star-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" class="star-btn {{ $i <= $resena->valoracion ? 'text-yellow-500' : 'text-gray-300' }} hover:text-yellow-500 transition-colors" data-rating="{{ $i }}">
                                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="valoracion" id="valoracion" value="{{ $resena->valoracion }}" required>
                            @if($errors->has('valoracion'))
                                <p class="text-red-500 text-sm mt-1">{{ $errors->first('valoracion') }}</p>
                            @endif
                        </div>

                        <!-- Comentario -->
                        <div class="mb-6">
                            <label for="comentario" class="block text-sm font-medium text-gray-700 mb-2">Comentario</label>
                            <textarea name="comentario" id="comentario" rows="5" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none" 
                                    placeholder="Comparte tu opinión sobre este cómic..." 
                                    required>{{ old('comentario', $resena->comentario) }}</textarea>
                            @if($errors->has('comentario'))
                                <p class="text-red-500 text-sm mt-1">{{ $errors->first('comentario') }}</p>
                            @endif
                        </div>

                        <!-- Botones -->
                        <div class="flex space-x-4">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                                Actualizar reseña
                            </button>
                            <a href="{{ route('user.resenas') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sistema de estrellas para valoración
            const starButtons = document.querySelectorAll('.star-btn');
            const valoracionInput = document.getElementById('valoracion');
            let selectedRating = {{ $resena->valoracion }};
            
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
            
            // Restaurar estrellas al salir del hover
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
    </script>
</x-app-layout> 