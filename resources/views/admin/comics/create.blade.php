@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Añadir Nuevo Cómic</h1>
        <a href="{{ route('admin.comics.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            Volver al Listado
        </a>
    </div>
    
    <!-- Mensaje para confirmar que el botón está presente -->
    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
        <p>Al final del formulario encontrarás un botón verde para guardar el cómic.</p>
    </div>
    
    <!-- Formulario para crear cómic -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.comics.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Título -->
                <div>
                    <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                    <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" required 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('titulo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Autor -->
                <div>
                    <label for="id_autor" class="block text-sm font-medium text-gray-700 mb-1">Autor</label>
                    <select name="id_autor" id="id_autor" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="">Seleccionar autor</option>
                        @foreach($autores as $autor)
                        <option value="{{ $autor->id_autor }}" {{ old('id_autor') == $autor->id_autor ? 'selected' : '' }}>
                            {{ $autor->nombre }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_autor')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Precio -->
                <div>
                    <label for="precio" class="block text-sm font-medium text-gray-700 mb-1">Precio (€)</label>
                    <input type="number" step="0.01" name="precio" id="precio" value="{{ old('precio') }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('precio')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Stock -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                    <input type="number" name="stock" id="stock" min="0" value="{{ old('stock') }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('stock')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Portada -->
                <div class="md:col-span-2">
                    <label for="portada_url" class="block text-sm font-medium text-gray-700 mb-1">Portada</label>
                    <input type="file" name="portada_url" id="portada_url" accept="image/*"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <p class="text-sm text-gray-500 mt-1">Formato: JPG, PNG. Tamaño máximo: 2MB</p>
                    @error('portada_url')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Archivo del Cómic -->
                <div class="md:col-span-2">
                    <label for="archivo_comic" class="block text-sm font-medium text-gray-700 mb-1">Archivo del Cómic</label>
                    <input type="file" name="archivo_comic" id="archivo_comic" accept=".pdf,.cbz,.cbr"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <p class="text-sm text-gray-500 mt-1">Formatos permitidos: PDF, CBZ, CBR. Tamaño máximo: 20MB</p>
                    @error('archivo_comic')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Descripción -->
                <div class="md:col-span-2">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="4" required
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Botón de Guardar -->
            <div class="mt-8 bg-gray-100 p-6 rounded-lg border-2 border-green-500">
                <div class="flex justify-center">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-8 rounded-lg text-xl shadow-xl">
                        GUARDAR CÓMIC
                    </button>
                </div>
                <p class="text-center mt-4 text-gray-600">👆 Haz clic en el botón para guardar el cómic</p>
            </div>
        </form>
    </div>
</div>
@endsection