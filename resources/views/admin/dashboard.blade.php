<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="flex flex-row">
                    <!-- Sidebar / Menú lateral -->
                    <div class="w-64 bg-gray-100 p-4 min-h-screen">
                        <h2 class="text-xl font-bold mb-6 text-gray-700">Gestión</h2>
                        
                        <nav>
                            <ul>
                                <li class="mb-2">
                                    <a href="{{ route('admin.comics.create') }}" class="block p-2 rounded hover:bg-gray-200 transition">
                                        Añadir Cómic
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('admin.comics.index') }}" class="block p-2 rounded hover:bg-gray-200 transition">
                                        Editar/eliminar Cómic
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('admin.users.index') }}" class="block p-2 rounded hover:bg-gray-200 transition">
                                        Usuarios
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('logout') }}" class="block p-2 rounded hover:bg-gray-200 transition"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Cerrar Sesion
                                    </a>
                                </li>
                            </ul>
                            <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                                @csrf
                            </form>
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