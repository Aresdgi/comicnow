<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="flex flex-row">
                    <!-- Sidebar / Menú lateral -->
                    <div class="w-64 bg-gray-100 p-4 min-h-screen">
                        <h2 class="text-xl font-bold mb-6 text-gray-700">Mi Cuenta</h2>
                        
                        <nav>
                            <ul>
                                <li class="mb-2">
                                    <a href="{{ route('biblioteca.index') }}" class="block p-2 rounded hover:bg-gray-200 transition">
                                        Mi Biblioteca
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('pedidos.index') }}" class="block p-2 rounded hover:bg-gray-200 transition">
                                        Mis Pedidos
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('user.resenas') }}" class="block p-2 rounded hover:bg-gray-200 transition">
                                        Mis Reseñas
                                    </a>
                                </li>
                                <li class="mt-10">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full p-2 rounded hover:bg-gray-200 transition text-left">
                                            Cerrar Sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <!-- Main Content Area -->
                    <div class="flex-1 p-6">
                        <div class="mb-10">
                            <h1 class="text-3xl font-bold mb-6">Mi Área</h1>
                            
                            <div class="flex items-center space-x-6">
                                <div>
                                    <h2 class="text-2xl mb-2">Editar Perfil</h2>
                                    <p class="mb-1">{{ Auth::user()->name }}</p>
                                    <p class="text-gray-600">{{ Auth::user()->email }}</p>
                                    <a href="{{ route('profile.show') }}" class="inline-block mt-4 px-4 py-2 bg-amber-500 text-white rounded hover:bg-amber-600 transition">
                                        Editar
                                    </a>
                                </div>
                                <div>
                                    <img src="{{ Auth::user()->profile_photo_url }}" alt="Foto de perfil" class="rounded-full h-24 w-24 object-cover">
                                </div>
                            </div>
                        </div>

                        <!-- Mis Cómics Recientes -->
                        <div class="mb-10">
                            <h2 class="text-2xl font-semibold mb-4">Mis Cómics Recientes</h2>
                            
                            @if(count($comicsRecientes ?? []) > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($comicsRecientes as $comic)
                                        <div class="bg-white rounded-lg shadow overflow-hidden">
                                            <div class="h-40 bg-gray-300 relative">
                                                <img src="{{ $comic->comic->portada_url }}" alt="{{ $comic->comic->titulo }}" class="w-full h-full object-cover">
                                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-3">
                                                    <a href="{{ route('biblioteca.leer', $comic->comic->id_comic) }}" class="text-white hover:text-amber-300">Continuar leyendo</a>
                                                </div>
                                            </div>
                                            <div class="p-4">
                                                <h3 class="font-semibold">{{ $comic->comic->titulo }}</h3>
                                                <p class="text-gray-600 text-sm">{{ $comic->comic->autor->nombre }}</p>
                                                <div class="flex justify-between items-center mt-2">
                                                    <span class="text-sm text-gray-500">Último acceso: {{ $comic->updated_at->format('d/m/Y') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-white p-6 rounded-lg shadow text-center">
                                    <p class="text-gray-500">No tienes cómics en tu biblioteca aún</p>
                                    <a href="{{ route('comics.index') }}" class="mt-3 inline-block px-4 py-2 bg-amber-500 text-white rounded hover:bg-amber-600 transition">
                                        Explorar Cómics
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Mis Pedidos Recientes -->
                        <div class="mb-10">
                            <h2 class="text-2xl font-semibold mb-4">Mis Pedidos Recientes</h2>
                            
                            @if(count($pedidosRecientes ?? []) > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white rounded-lg overflow-hidden">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="py-2 px-4 text-left">#Pedido</th>
                                                <th class="py-2 px-4 text-left">Fecha</th>
                                                <th class="py-2 px-4 text-left">Total</th>
                                                <th class="py-2 px-4 text-left">Estado</th>
                                                <th class="py-2 px-4 text-left">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pedidosRecientes as $pedido)
                                                <tr class="border-t">
                                                    <td class="py-2 px-4">{{ $pedido->id_pedido }}</td>
                                                    <td class="py-2 px-4">{{ $pedido->fecha }}</td>
                                                    <td class="py-2 px-4">{{ $pedido->total ?? '0.00' }}€</td>
                                                    <td class="py-2 px-4">
                                                        <span class="px-2 py-1 rounded text-xs 
                                                            @if($pedido->estado == 'completado') bg-green-100 text-green-800
                                                            @elseif($pedido->estado == 'pendiente') bg-yellow-100 text-yellow-800
                                                            @else bg-blue-100 text-blue-800 @endif">
                                                            {{ ucfirst($pedido->estado) }}
                                                        </span>
                                                    </td>
                                                    <td class="py-2 px-4">
                                                        <a href="{{ route('pedidos.show', $pedido->id_pedido) }}" class="text-blue-500 hover:underline">Ver detalles</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="bg-white p-6 rounded-lg shadow text-center">
                                    <p class="text-gray-500">No tienes pedidos recientes</p>
                                    <a href="{{ route('comics.index') }}" class="mt-3 inline-block px-4 py-2 bg-amber-500 text-white rounded hover:bg-amber-600 transition">
                                        Explorar Cómics
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Recomendaciones -->
                        <div>
                            <h2 class="text-2xl font-semibold mb-4">Recomendados Para Ti</h2>
                            
                            @if(count($recomendaciones ?? []) > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($recomendaciones as $comic)
                                        <div class="bg-white rounded-lg shadow overflow-hidden">
                                            <img src="{{ $comic->portada_url }}" alt="{{ $comic->titulo }}" class="w-full h-48 object-cover">
                                            <div class="p-4">
                                                <h3 class="font-semibold">{{ $comic->titulo }}</h3>
                                                <p class="text-gray-600 text-sm">{{ $comic->autor->nombre }}</p>
                                                <div class="flex justify-between items-center mt-2">
                                                    <span class="font-bold">{{ $comic->precio }}€</span>
                                                    <a href="{{ route('comics.show', $comic->id_comic) }}" class="text-amber-500 hover:text-amber-700">
                                                        Ver comic
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-white p-6 rounded-lg shadow text-center">
                                    <p class="text-gray-500">No hay recomendaciones disponibles aún</p>
                                    <a href="{{ route('comics.index') }}" class="mt-3 inline-block px-4 py-2 bg-amber-500 text-white rounded hover:bg-amber-600 transition">
                                        Explorar Cómics
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>