@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Mi Biblioteca</h1>
    
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
    
    <!-- Listado de cómics en la biblioteca del usuario -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse ($biblioteca as $item)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="h-48 overflow-hidden">
                    @if($item->comic->portada_url)
                        <img src="{{ asset('storage/' . $item->comic->portada_url) }}" alt="{{ $item->comic->titulo }}" 
                            class="w-full h-full object-cover transition-transform hover:scale-105">
                    @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500">Sin imagen</span>
                        </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold mb-2">{{ $item->comic->titulo }}</h3>
                    
                    <!-- Barra de progreso de lectura -->
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $item->progreso_lectura }}%"></div>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">Progreso: {{ $item->progreso_lectura }}%</p>
                    
                    <div class="flex justify-between">
                        <a href="{{ route('biblioteca.leer', $item->comic->id_comic) }}" 
                            class="bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700">
                            Leer
                        </a>
                        <form action="{{ route('biblioteca.eliminar', $item->comic->id_comic) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m4-7.5V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-yellow-50 p-6 rounded-lg border border-yellow-200">
                <h3 class="text-lg text-yellow-800 font-medium mb-2">Tu biblioteca está vacía</h3>
                <p class="text-yellow-700">No tienes cómics en tu biblioteca. Visita nuestro <a href="{{ route('comics.index') }}" class="underline">catálogo</a> para añadir algunos.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection