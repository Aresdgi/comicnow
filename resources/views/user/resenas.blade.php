<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-6">Mis Reseñas</h1>
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(count($resenas ?? []) > 0)
                        <div class="space-y-6">
                            @foreach($resenas as $resena)
                                <div class="bg-white rounded-lg shadow p-6">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-4">
                                            <img src="{{ $resena->comic->portada_url }}" alt="{{ $resena->comic->titulo }}" class="w-20 h-28 object-cover rounded">
                                        </div>
                                        <div class="flex-grow">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h2 class="text-lg font-semibold">{{ $resena->comic->titulo }}</h2>
                                                    <p class="text-sm text-gray-600">{{ $resena->comic->autor->nombre }}</p>
                                                </div>
                                                <div class="flex space-x-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-5 h-5 {{ $i <= $resena->puntuacion ? 'text-yellow-500' : 'text-gray-300' }}" 
                                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    @endfor
                                                </div>
                                            </div>
                                            <p class="mt-2 text-gray-700">{{ $resena->comentario }}</p>
                                            <div class="mt-4 text-sm text-gray-500">
                                                {{ $resena->created_at->format('d/m/Y') }}
                                            </div>
                                            <div class="mt-2 flex space-x-3">
                                                <a href="{{ route('user.resenas.edit', $resena->id) }}" class="text-blue-600 hover:text-blue-800">
                                                    Editar
                                                </a>
                                                <form action="{{ route('user.resenas.destroy', $resena->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('¿Estás seguro de que deseas eliminar esta reseña?')">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white p-6 rounded-lg shadow text-center">
                            <p class="text-gray-500 mb-4">Todavía no has escrito ninguna reseña</p>
                            <a href="{{ route('comics.index') }}" class="inline-block px-4 py-2 bg-amber-500 text-white rounded hover:bg-amber-600 transition">
                                Explorar Cómics para Reseñar
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>