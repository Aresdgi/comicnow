@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Editar Autor: {{ $autor->nombre }}</h1>
        <a href="{{ route('admin.autores.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <form action="{{ route('admin.autores.update', $autor->id_autor) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $autor->nombre) }}" required 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('nombre')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Editorial -->
                <div>
                    <label for="editorial" class="block text-sm font-medium text-gray-700 mb-1">Editorial *</label>
                    <input type="text" name="editorial" id="editorial" value="{{ old('editorial', $autor->editorial) }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('editorial')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Comisión -->
                <div class="md:col-span-2">
                    <label for="comision" class="block text-sm font-medium text-gray-700 mb-1">Comisión (%) *</label>
                    <input type="number" name="comision" id="comision" value="{{ old('comision', $autor->comision) }}" required
                           min="0" max="100" step="0.01"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('comision')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Biografía -->
                <div class="md:col-span-2">
                    <label for="biografia" class="block text-sm font-medium text-gray-700 mb-1">Biografía</label>
                    <textarea name="biografia" id="biografia" rows="5"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">{{ old('biografia', $autor->biografia) }}</textarea>
                    @error('biografia')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Botones -->
            <div class="flex justify-end mt-6 space-x-3">
                <a href="{{ route('admin.autores.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-save mr-2"></i>Actualizar Autor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 