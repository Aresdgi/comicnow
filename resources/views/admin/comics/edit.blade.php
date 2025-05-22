@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Editar Cómic</h1>
        <a href="{{ route('admin.comics.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            Volver al Listado
        </a>
    </div>
    
    <!-- Mensaje para confirmar que el botón está presente -->
    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
        <p>Al final del formulario encontrarás un botón verde para guardar los cambios.</p>
    </div>
    
    <!-- Formulario para editar cómic -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.comics.update', $comic->id_comic) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Título -->
                <div>
                    <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                    <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $comic->titulo) }}" required 
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
                        <option value="{{ $autor->id_autor }}" {{ (old('id_autor', $comic->id_autor) == $autor->id_autor) ? 'selected' : '' }}>
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
                    <input type="number" step="0.01" name="precio" id="precio" value="{{ old('precio', $comic->precio) }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('precio')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Stock -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                    <input type="number" name="stock" id="stock" min="0" value="{{ old('stock', $comic->stock) }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('stock')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Portada Actual -->
                <div class="md:col-span-2">
                    <p class="block text-sm font-medium text-gray-700 mb-1">Portada Actual</p>
                    @if($comic->portada_url)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $comic->portada_url) }}" alt="{{ $comic->titulo }}" class="h-32 object-cover">
                    </div>
                    @else
                    <p class="text-gray-500 italic">Sin portada</p>
                    @endif
                </div>
                
                <!-- Nueva Portada -->
                <div class="md:col-span-2">
                    <label for="portada_url" class="block text-sm font-medium text-gray-700 mb-1">Nueva Portada</label>
                    <input type="file" name="portada_url" id="portada_url" accept="image/*"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <p class="text-sm text-gray-500 mt-1">Formato: JPG, PNG. Tamaño máximo: 2MB. Deja en blanco para mantener la portada actual.</p>
                    @error('portada_url')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Archivo Actual -->
                <div class="md:col-span-2">
                    <p class="block text-sm font-medium text-gray-700 mb-1">Archivo Actual</p>
                    @if($comic->archivo_comic)
                    <p class="text-gray-700">
                        <svg class="inline-block h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Archivo cargado
                    </p>
                    @else
                    <p class="text-gray-500 italic">Sin archivo</p>
                    @endif
                </div>
                
                <!-- Nuevo Archivo -->
                <div class="md:col-span-2">
                    <label for="archivo_comic" class="block text-sm font-medium text-gray-700 mb-1">Nuevo Archivo del Cómic</label>
                    <input type="file" name="archivo_comic" id="archivo_comic" accept=".pdf,.cbz,.cbr"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <p class="text-sm text-gray-500 mt-1">Formatos permitidos: PDF, CBZ, CBR. Tamaño máximo: 20MB. Deja en blanco para mantener el archivo actual.</p>
                    @error('archivo_comic')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Descripción -->
                <div class="md:col-span-2">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="4" required
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">{{ old('descripcion', $comic->descripcion) }}</textarea>
                    @error('descripcion')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Botón de Guardar Cambios -->
            <div class="mt-8 bg-gray-100 p-6 rounded-lg border-2 border-green-500">
                <div class="flex justify-center">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-8 rounded-lg text-xl shadow-xl">
                        GUARDAR CAMBIOS
                    </button>
                </div>
                <p class="text-center mt-4 text-gray-600">👆 Haz clic en el botón para guardar los cambios</p>
            </div>
        </form>
    </div>
</div>
@endsection