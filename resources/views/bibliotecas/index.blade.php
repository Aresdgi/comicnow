@extends('layouts.app')

@section('content')
<div class="container mx-auto px-2 sm:px-4 py-4 sm:py-8">
    <h1 class="text-2xl sm:text-3xl font-bold mb-4 sm:mb-6 px-2 sm:px-0">Mi Biblioteca</h1>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 mx-2 sm:mx-0">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 mx-2 sm:mx-0">
            {{ session('error') }}
        </div>
    @endif
    
    <!-- Listado de cómics en la biblioteca del usuario -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-6 px-2 sm:px-0">
        @forelse ($biblioteca as $item)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="h-40 sm:h-48 overflow-hidden">
                    @if($item->comic->portada_url)
                        <img src="{{ asset('storage/' . $item->comic->portada_url) }}" alt="{{ $item->comic->titulo }}" 
                            class="w-full h-full object-cover transition-transform hover:scale-105">
                    @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500 text-sm">Sin imagen</span>
                        </div>
                    @endif
                </div>
                <div class="p-3 sm:p-4">
                    <h3 class="text-base sm:text-lg font-semibold mb-4 line-clamp-2">{{ $item->comic->titulo }}</h3>
                    
                    <div>
                        <a href="{{ route('biblioteca.leer', $item->comic->id_comic) }}" 
                            class="bg-indigo-600 text-white px-6 py-2 rounded text-sm font-medium hover:bg-indigo-700 transition-colors w-full block text-center">
                            Leer
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-yellow-50 p-4 sm:p-6 rounded-lg border border-yellow-200 mx-2 sm:mx-0">
                <h3 class="text-base sm:text-lg text-yellow-800 font-medium mb-2">Tu biblioteca está vacía</h3>
                <p class="text-sm sm:text-base text-yellow-700">No tienes cómics en tu biblioteca. Visita nuestro <a href="{{ route('comics.index') }}" class="underline hover:text-yellow-800">catálogo</a> para añadir algunos.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection