@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">{{ $comic->titulo }}</h1>
        <div class="flex space-x-2">
            <a href="{{ route('comics.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Volver al Listado
            </a>
        </div>
    </div>
    
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="md:flex">
            <!-- Columna izquierda: Imagen y autor -->
            <div class="md:w-1/3 p-6 border-r">
                <div class="mb-6">
                    @if($comic->portada_url)
                    <img src="{{ asset('storage/' . $comic->portada_url) }}" alt="{{ $comic->titulo }}" class="comic-detail-image rounded-lg shadow-lg w-full h-auto">
                    @else
                    <div class="bg-gray-200 h-64 w-full flex items-center justify-center rounded-lg">
                        <p class="text-gray-500">Sin imagen</p>
                    </div>
                    @endif
                </div>
                
                <div class="mt-4">
                    <p class="text-sm text-gray-600 font-medium">Autor</p>
                    <p class="text-gray-800 mb-4">{{ $comic->autor->nombre ?? 'Desconocido' }}</p>
                </div>
            </div>
            
            <!-- Columna derecha: Información -->
            <div class="md:w-2/3 p-6">
                <div class="mb-4">
                    <p class="text-sm text-gray-600 font-medium">Precio</p>
                    <p class="text-xl font-bold text-green-600">{{ number_format($comic->precio, 2) }}€</p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-600 font-medium">Categoría</p>
                    <p class="text-gray-800">{{ $comic->categoria }}</p>
                </div>
                
                <div class="mb-6">
                    <p class="text-sm text-gray-600 font-medium">Descripción</p>
                    <p class="text-gray-800">{{ $comic->descripcion ?? 'Sin descripción disponible' }}</p>
                </div>
                
                <!-- Contenedor para mensajes -->
                <div id="message-container"></div>
                
                <!-- Formulario para añadir al carrito -->
                <div class="mt-4">
                    <form id="add-to-cart-form" action="{{ route('carrito.agregar') }}" method="POST" class="flex items-end space-x-2">
                        @csrf
                        <input type="hidden" name="id_comic" value="{{ $comic->id_comic }}">
                        
                        <div class="flex flex-col">
                            <label for="cantidad" class="text-sm text-gray-600 mb-1">Cantidad:</label>
                            <input type="number" name="cantidad" id="cantidad" value="1" min="1" class="border rounded p-2 w-20">
                        </div>
                        
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-5 rounded text-center" style="background-color: #0052CC;">
                            Añadir al carrito
                        </button>
                    </form>
                </div>
                
                @if($comic->resenas && $comic->resenas->count() > 0)
                <div class="mt-8">
                    <h3 class="text-lg font-semibold mb-4">Reseñas ({{ $comic->resenas->count() }})</h3>
                    <div class="space-y-4">
                        @foreach($comic->resenas as $resena)
                        <div class="bg-gray-50 p-4 rounded-md">
                            <div class="flex justify-between">
                                <p class="font-medium">{{ $resena->usuario->name ?? 'Usuario anónimo' }}</p>
                                <p class="text-sm text-gray-600">{{ $resena->fecha ?? $resena->created_at->format('d/m/Y') }}</p>
                            </div>
                            <div class="mt-1">
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $resena->valoracion)
                                        <svg class="h-4 w-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        @else
                                        <svg class="h-4 w-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <p class="mt-2 text-gray-700">{{ $resena->comentario }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/carrito.js') }}"></script>
@endpush
@endsection