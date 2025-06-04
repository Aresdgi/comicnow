<footer class="bg-gradient-to-b from-[#111827] to-[#4a5c6a] text-white">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
            <!-- Columna Principal - ComicNow -->
            <div class="md:col-span-2 space-y-6">
                <!-- Título ComicNow -->
                <h1 class="text-3xl md:text-4xl font-bold text-yellow-400 uppercase tracking-wider">
                    ComicNow
                </h1>
                
                <!-- Texto de contacto -->
                <p class="text-amber-100 text-lg">
                    ¿Dudas? ¡Llámanos o mándanos una señal en el cielo!
                </p>
                
                <!-- Número de teléfono -->
                <h2 class="text-2xl md:text-3xl font-bold text-white uppercase">
                    956 000 000
                </h2>
                
                <!-- Horario de atención -->
                <div class="text-amber-100 space-y-2">
                    <p class="font-bold text-lg">Horario de atención:</p>
                    <ul class="space-y-1">
                        <li><strong>Lunes a Viernes:</strong> 10:00 – 20:00</li>
                        <li><strong>Sábados:</strong> 11:00 – 18:00</li>
                        <li><strong>Domingos:</strong> Cerrado (¡hasta los superhéroes descansan!)</li>
                    </ul>
                </div>
            </div>
            
            <!-- Columna Productos -->
            <div class="space-y-4">
                <h5 class="text-orange-300 text-xl font-bold uppercase tracking-wide">
                    Navegación
                </h5>
                <nav class="space-y-3">
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('comics.index') }}" class="text-amber-100 hover:text-white hover:border-b hover:border-orange-400 transition-colors duration-200 text-lg cursor-pointer">
                                Todos los cómics
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('home') }}" class="text-amber-100 hover:text-white hover:border-b hover:border-orange-400 transition-colors duration-200 text-lg cursor-pointer">
                                Inicio
                            </a>
                        </li>
                        <li>
                            <a href="#sobre-nosotros" class="text-amber-100 hover:text-white hover:border-b hover:border-orange-400 transition-colors duration-200 text-lg cursor-pointer">
                                Sobre nosotros
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            
            <!-- Columna Mi Cuenta -->
            <div class="space-y-4">
                <h5 class="text-orange-300 text-xl font-bold uppercase tracking-wide">
                    Mi Cuenta
                </h5>
                <nav class="space-y-3">
                    <ul class="space-y-2">
                        @auth
                        <li>
                            <a href="{{ route('profile.show') }}" class="text-amber-100 hover:text-white hover:border-b hover:border-orange-400 transition-colors duration-200 text-lg cursor-pointer">
                                Mi perfil
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('biblioteca.index') }}" class="text-amber-100 hover:text-white hover:border-b hover:border-orange-400 transition-colors duration-200 text-lg cursor-pointer">
                                Mi biblioteca
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('carrito.index') }}" class="text-amber-100 hover:text-white hover:border-b hover:border-orange-400 transition-colors duration-200 text-lg cursor-pointer">
                                Carrito
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pedidos.index') }}" class="text-amber-100 hover:text-white hover:border-b hover:border-orange-400 transition-colors duration-200 text-lg cursor-pointer">
                                Mis pedidos
                            </a>
                        </li>
                        @else
                        <li>
                            <a href="{{ route('login') }}" class="text-amber-100 hover:text-white hover:border-b hover:border-orange-400 transition-colors duration-200 text-lg cursor-pointer">
                                Iniciar sesión
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}" class="text-amber-100 hover:text-white hover:border-b hover:border-orange-400 transition-colors duration-200 text-lg cursor-pointer">
                                Registrarse
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('carrito.index') }}" class="text-amber-100 hover:text-white hover:border-b hover:border-orange-400 transition-colors duration-200 text-lg cursor-pointer">
                                Carrito
                            </a>
                        </li>
                        @endauth
                    </ul>
                </nav>
            </div>
            
            <!-- Columna Legal -->
            <div class="space-y-4">
                <h5 class="text-orange-300 text-xl font-bold uppercase tracking-wide">
                    Legal
                </h5>
                <nav class="space-y-3">
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('policy.show') }}" class="text-amber-100 hover:text-white hover:border-b hover:border-orange-400 transition-colors duration-200 text-lg cursor-pointer">
                                Política de privacidad
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('terms.show') }}" class="text-amber-100 hover:text-white hover:border-b hover:border-orange-400 transition-colors duration-200 text-lg cursor-pointer">
                                Términos de uso
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('cookies.show') }}" class="text-amber-100 hover:text-white hover:border-b hover:border-orange-400 transition-colors duration-200 text-lg cursor-pointer">
                                Política de Cookies
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</footer> 