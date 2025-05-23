@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detalles del Cómic</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.comics.edit', $comic->id_comic) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                Editar
            </a>
            <a href="{{ route('admin.comics.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
        </div>
    </div>
    
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="md:flex">
            <!-- Columna izquierda: Imagen y acciones -->
            <div class="md:w-1/3 p-6 border-r">
                <div class="mb-6">
                    @if($comic->portada_url)
                    <img src="{{ asset('storage/' . $comic->portada_url) }}" alt="{{ $comic->titulo }}" class="w-full h-auto object-cover rounded-lg shadow-lg">
                    @else
                    <div class="bg-gray-200 h-64 w-full flex items-center justify-center rounded-lg">
                        <p class="text-gray-500">Sin imagen</p>
                    </div>
                    @endif
                </div>
                
                <div class="mt-4 flex flex-col space-y-2">
                    <a href="{{ route('admin.comics.edit', $comic->id_comic) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-center">
                        Editar Cómic
                    </a>
                    
                    <form action="{{ route('admin.comics.destroy', $comic->id_comic) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este cómic?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded w-full">
                            Eliminar Cómic
                        </button>
                    </form>
                    
                    @if($comic->archivo_comic)
                    <a href="{{ asset('storage/' . $comic->archivo_comic) }}" target="_blank" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center">
                        Ver Archivo
                    </a>
                    @endif
                </div>
            </div>
            
            <!-- Columna derecha: Información -->
            <div class="md:w-2/3 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $comic->titulo }}</h2>
                        
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 font-medium">Autor</p>
                            <p class="text-gray-800">{{ $comic->autor->nombre ?? 'Desconocido' }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 font-medium">Precio</p>
                            <p class="text-xl font-bold text-green-600">€{{ number_format($comic->precio, 2) }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 font-medium">Categoría</p>
                            <p class="text-gray-800">{{ $comic->categoria }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 font-medium">ID</p>
                            <p class="text-gray-800">{{ $comic->id_comic }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 font-medium">Fecha de Creación</p>
                            <p class="text-gray-800">{{ $comic->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 font-medium">Última Actualización</p>
                            <p class="text-gray-800">{{ $comic->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600 font-medium mb-2">Descripción</p>
                        <p class="text-gray-800">{{ $comic->descripcion }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection