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
                        <div>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>