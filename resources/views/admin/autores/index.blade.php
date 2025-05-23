@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Gestión de Autores</h1>
        <a href="{{ route('admin.autores.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Nuevo Autor
        </a>
    </div>

    <!-- Mensajes de éxito/error -->
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

    <!-- Tabla de autores -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Editorial</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comisión</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cómics</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($autores as $autor)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $autor->nombre }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $autor->editorial }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $autor->comision }}%</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $autor->comics_count }} cómics
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.autores.show', $autor->id_autor) }}" class="text-blue-600 hover:text-blue-900 px-2 py-1 rounded border border-blue-600 hover:bg-blue-50">
                                Ver
                            </a>
                            <a href="{{ route('admin.autores.edit', $autor->id_autor) }}" class="text-yellow-600 hover:text-yellow-900 px-2 py-1 rounded border border-yellow-600 hover:bg-yellow-50">
                                Editar
                            </a>
                            @if($autor->comics_count == 0)
                                <form class="inline" method="POST" action="{{ route('admin.autores.destroy', $autor->id_autor) }}" onsubmit="return confirm('¿Estás seguro de eliminar este autor?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 px-2 py-1 rounded border border-red-600 hover:bg-red-50">
                                        Eliminar
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-400 px-2 py-1 rounded border border-gray-300 cursor-not-allowed" title="No se puede eliminar: tiene cómics asociados">
                                    Eliminar
                                </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        No hay autores registrados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if($autores->hasPages())
        <div class="mt-6">
            {{ $autores->links() }}
        </div>
    @endif
</div>
@endsection 