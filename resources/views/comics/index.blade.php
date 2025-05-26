<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Biblioteca de Cómics</h1>
                
                <!-- Buscador Simple -->
                <div class="mb-8 bg-gray-50 p-6 rounded-lg">
                    <form method="GET" action="{{ route('comics.index') }}" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <input type="text" 
                                   name="buscar" 
                                   placeholder="Buscar por título o autor..." 
                                   value="{{ request('buscar') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" 
                                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500">
                                Buscar
                            </button>
                            @if(request('buscar'))
                                <a href="{{ route('comics.index') }}" 
                                   class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                                    Limpiar
                                </a>
                            @endif
                        </div>
                    </form>
                    
                    @if(request('buscar'))
                        <p class="text-sm text-gray-600 mt-2">
                            Mostrando resultados para: "<strong>{{ request('buscar') }}</strong>"
                        </p>
                    @endif
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @forelse($comics ?? [] as $comic)
                        <div class="bg-white rounded-lg shadow overflow-hidden flex flex-col">
                            <!-- Contenedor de imagen con altura automática -->
                            <div class="min-h-64 h-auto overflow-hidden flex-grow">
                                <img class="mx-auto h-auto max-h-96 max-w-full" 
                                    src="{{ $comic->portada_url ? asset('storage/' . $comic->portada_url) : 'https://via.placeholder.com/300x400?text=Comic' }}" 
                                    alt="{{ $comic->titulo ?? 'Comic' }}">
                            </div>
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $comic->titulo ?? 'Título del Comic' }}</h3>
                                <p class="text-sm text-gray-600">{{ $comic->autor->nombre ?? 'Autor' }}</p>
                                <div class="mt-4 flex justify-between items-center">
                                    <span class="text-green-700 font-medium">{{ $comic->precio ?? '$0.00' }}€</span>
                                    <a href="{{ route('comics.show', $comic) }}" class="px-3 py-1 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">Ver</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            @if(request('buscar'))
                                <p class="text-gray-500 text-lg">No se encontraron cómics que coincidan con tu búsqueda.</p>
                                <a href="{{ route('comics.index') }}" class="mt-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                    Ver todos los cómics
                                </a>
                            @else
                                <p class="text-gray-500 text-lg">No hay cómics disponibles en este momento.</p>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>