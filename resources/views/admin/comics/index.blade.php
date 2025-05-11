@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Administración de Cómics</h1>
        <a href="{{ route('admin.comics.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Añadir Nuevo Cómic
        </a>
    </div>
    
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif
    
    @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif
    
    <!-- Tabla de cómics -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">ID</th>
                    <th class="py-3 px-4 text-left">Portada</th>
                    <th class="py-3 px-4 text-left">Título</th>
                    <th class="py-3 px-4 text-left">Autor</th>
                    <th class="py-3 px-4 text-left">Precio</th>
                    <th class="py-3 px-4 text-left">Stock</th>
                    <th class="py-3 px-4 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($comics as $comic)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4">{{ $comic->id_comic }}</td>
                    <td class="py-3 px-4">
                        @if($comic->portada_url)
                        <img src="{{ asset('storage/' . $comic->portada_url) }}" alt="{{ $comic->titulo }}" class="h-16 w-auto object-cover">
                        @else
                        <span class="text-gray-400">Sin portada</span>
                        @endif
                    </td>
                    <td class="py-3 px-4">{{ $comic->titulo }}</td>
                    <td class="py-3 px-4">{{ $comic->autor->nombre ?? 'Sin autor' }}</td>
                    <td class="py-3 px-4">€{{ $comic->precio }}</td>
                    <td class="py-3 px-4">{{ $comic->stock }}</td>
                    <td class="py-3 px-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.comics.show', $comic->id_comic) }}" class="text-blue-600 hover:text-blue-900">Ver</a>
                            <a href="{{ route('admin.comics.edit', $comic->id_comic) }}" class="text-yellow-600 hover:text-yellow-900">Editar</a>
                            
                            <form action="{{ route('admin.comics.destroy', $comic->id_comic) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este cómic?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                
                @if($comics->count() === 0)
                <tr>
                    <td colspan="7" class="py-4 px-4 text-center text-gray-500">No hay cómics registrados.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection