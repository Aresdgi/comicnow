<x-app-layout>
    <div class="home-dark-bg">
        <!-- Hero Section -->
        <section class="py-16">
            <div class="container mx-auto px-4 flex flex-col-reverse md:flex-row items-center gap-8">
                <!-- Texto -->
                <div class="md:w-1/2 space-y-6">
                    <h1 class="text-4xl md:text-5xl font-bold text-white">
                        Descubre el mundo de los cómics digitales.
                    </h1>
                    <p class="text-gray-300">
                        Explora nuestra gran variedad de cómics en formato PDF
                    </p>
                    <div class="flex space-x-4">
                        <a href="{{ route('comics.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-semibold py-2 px-6 rounded">
                            Ver Comics
                        </a>
                        <a href="{{ route('about') }}" class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded">
                            Más Información
                        </a>
                    </div>
                </div>
                <!-- Imágenes -->
                <div class="md:w-1/2 grid grid-cols-2 gap-0">
                    <img src="https://hablandodecomics.wordpress.com/wp-content/uploads/2012/01/portada-95-x-men.jpg?w=723" alt="X-Men Comic" class="w-36 h-auto rounded shadow-lg mx-auto" />
                    <img src="https://sm.ign.com/t/ign_es/screenshot/b/bthe-aveng/bthe-avengers-vol-1-223bbrbrbdrawn-byb-ed-hanniganbrbrthe-no_3rxt.1080.jpg" alt="The Avengers Comic" class="w-36 h-auto rounded shadow-lg mx-auto" />
                    <img src="https://sm.ign.com/t/ign_es/screenshot/b/bgreen-lan/bgreen-lantern-vol-2-85bbrbrbdrawn-byb-neal-adamsbrbrsilver_7d9m.1080.jpg" alt="Green Lantern Comic" class="w-36 h-auto rounded shadow-lg mx-auto" />
                    <img src="https://sm.ign.com/t/ign_es/screenshot/b/bsilver-su/bsilver-surfer-vol-1-4bbrbrbdrawn-byb-john-buscemabrbrwhen-i_uyby.1080.jpg" alt="Silver Surfer Comic" class="w-36 h-auto rounded shadow-lg mx-auto" />
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-16">
            <div class="container mx-auto px-4 text-center space-y-6">
                <h2 class="text-3xl font-bold text-white">Todos los mejores comics estarán aquí</h2>
                <p class="text-gray-400">Sumérgete en las mejores historias</p>
            </div>

            <div class="container mx-auto px-4 mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="space-y-4">
                    <img src="https://sm.ign.com/t/ign_es/screenshot/b/bnick-fury/bnick-fury-agent-of-shield-4bbrbrbdrawn-byb-jim-sterankobrbr_r4n1.1080.jpg" alt="Cartland" class="w-[250px] h-auto rounded shadow-md mx-auto" />
                    <h3 class="text-xl font-semibold text-white">Los mejores comics</h3>
                    <p class="text-gray-400">
                        Encuentra cómics de todos los géneros y autores que más te gusten.
                    </p>
                </div>
                <!-- Feature 2 -->
                <div class="space-y-4">
                    <img src="https://sm.ign.com/t/ign_es/screenshot/b/bnick-fury/bnick-fury-agent-of-shield-4bbrbrbdrawn-byb-jim-sterankobrbr_r4n1.1080.jpg" alt="Jeremiah" class="w-[250px] h-auto rounded shadow-md mx-auto" />
                    <h3 class="text-xl font-semibold text-white">Un enorme catálogo</h3>
                    <p class="text-gray-400">
                        Disfruta de una amplia selección de cómics, con nuevas opciones de lectura y descargas.
                    </p>
                </div>
                <!-- Feature 3 -->
                <div class="space-y-4">
                    <img src="https://sm.ign.com/t/ign_es/screenshot/b/bnick-fury/bnick-fury-agent-of-shield-4bbrbrbdrawn-byb-jim-sterankobrbr_r4n1.1080.jpg" alt="Conan" class="w-[250px] h-auto rounded shadow-md mx-auto" />
                    <h3 class="text-xl font-semibold text-white">Interfaz amigable</h3>
                    <p class="text-gray-400">
                        Navega fácilmente y encuentra tus últimos cómics favoritos.
                    </p>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>