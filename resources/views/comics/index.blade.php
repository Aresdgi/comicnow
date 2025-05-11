@extends('layouts.landing')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Biblioteca de Cómics</h1>
            
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
                                <span class="text-gray-700 font-medium">{{ $comic->precio ?? '$0.00' }}</span>
                                <a href="{{ route('comics.show', $comic->id_comic) }}" class="px-3 py-1 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">Ver</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">No hay cómics disponibles en este momento.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection