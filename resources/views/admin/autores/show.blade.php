@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detalles del Autor</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.autores.edit', $autor->id_autor) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('admin.autores.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>
    </div>

    <!-- Información del autor -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-8">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ $autor->nombre }}</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <p class="text-sm font-medium text-gray-600">Editorial</p>
                    <p class="text-lg text-gray-800">{{ $autor->editorial }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-600">Comisión</p>
                    <p class="text-lg text-gray-800">{{ $autor->comision }}%</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-600">Cómics Publicados</p>
                    <p class="text-lg text-gray-800">{{ $comics->total() }} cómics</p>
                </div>
            </div>
            
            @if($autor->biografia)
            <div>
                <p class="text-sm font-medium text-gray-600 mb-2">Biografía</p>
                <p class="text-gray-700 leading-relaxed">{{ $autor->biografia }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Cómics del autor -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Cómics de {{ $autor->nombre }}</h3>
        </div>
        
        @if($comics->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Portada</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($comics as $comic)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($comic->portada_url)
                                <img src="{{ asset('storage/' . $comic->portada_url) }}" alt="{{ $comic->titulo }}" class="h-16 w-12 object-cover rounded">
                            @else
                                <div class="h-16 w-12 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fas fa-book text-gray-400"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $comic->titulo }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $comic->categoria }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($comic->precio, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.comics.show', $comic->id_comic) }}" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        @if($comics->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $comics->links() }}
            </div>
        @endif
        @else
        <div class="px-6 py-4 text-center text-gray-500">
            Este autor no tiene cómics publicados aún.
        </div>
        @endif
    </div>
</div>
@endsection 