<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 overflow-visible">
        <div class="flex justify-between h-16 overflow-visible">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex overflow-visible">
                    <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                        {{ __('Inicio') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('comics.index') }}" :active="request()->routeIs('comics.index')">
                        {{ __('Catálogo') }}
                    </x-nav-link>
                    @auth
                        <x-nav-link href="{{ route('biblioteca.index') }}" :active="request()->routeIs('biblioteca.index')">
                            {{ __('Tu Biblioteca') }}
                        </x-nav-link>
                        <x-nav-link
                            href="{{ route('carrito.index') }}"
                            :active="request()->routeIs('carrito.index')"
                            class="inline-flex items-center gap-1"
                        >
                            {{ __('Carrito') }}
                            @php
                                $carrito = session('carrito', []);
                                $cantidad = count($carrito);
                            @endphp
                            @if($cantidad > 0)
                                <span
                                    id="cart-badge"
                                    class="bg-red-600 text-white text-xs font-bold
                                           w-5 h-5 flex items-center justify-center
                                           rounded-full"
                                >
                                    {{ $cantidad }}
                                </span>
                            @endif
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Right Side Navigation -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <div class="flex items-center space-x-6">
                        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="text-gray-700 hover:text-gray-900">
                            {{ __('Perfil') }}
                        </x-nav-link>
                        
                        <div class="border-l border-gray-300 h-6"></div>
                        
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-700 px-3 py-2 text-sm font-medium transition duration-150 ease-in-out">
                                {{ __('Cerrar Sesión') }}
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center space-x-4">
                        <x-nav-link href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">
                            {{ __('Iniciar sesión') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-150 ease-in-out">
                            {{ __('Registrarse') }}
                        </x-nav-link>
                    </div>
                @endauth
            </div>

            <!-- Hamburger para móviles -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menú móvil -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                {{ __('Inicio') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('comics.index') }}" :active="request()->routeIs('comics.index')">
                {{ __('Catálogo') }}
            </x-responsive-nav-link>
            @auth
                <x-responsive-nav-link href="{{ route('biblioteca.index') }}" :active="request()->routeIs('biblioteca.index')">
                    {{ __('Tu Biblioteca') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('carrito.index') }}" :active="request()->routeIs('carrito.index')" class="flex items-center justify-between">
                    <span>{{ __('Carrito') }}</span>
                    @php
                        $carrito = session('carrito', []);
                        $cantidad = count($carrito);
                    @endphp
                    @if($cantidad > 0)
                        <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                            {{ $cantidad }}
                        </span>
                    @endif
                </x-responsive-nav-link>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Perfil') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Cerrar Sesión') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link href="{{ route('login') }}">
                        {{ __('Iniciar sesión') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('register') }}">
                        {{ __('Registrarse') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        @endauth
    </div>
</nav>
