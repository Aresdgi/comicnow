<x-app-layout>
    <div class="min-h-screen bg-gray-100 py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-8 prose prose-lg max-w-none">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Política de Cookies</h1>
                    
                    <p class="text-gray-600 mb-4">
                        <strong>Última actualización:</strong> {{ date('d/m/Y') }}
                    </p>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-6 mb-4">1. ¿Qué son las Cookies?</h2>
                    <p class="text-gray-700 mb-4">
                        Las cookies son pequeños archivos de texto que se almacenan en tu dispositivo cuando visitas ComicNow. 
                        Nos ayudan a mejorar tu experiencia, recordar tus preferencias y proporcionar funcionalidades esenciales del sitio.
                    </p>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-6 mb-4">2. Tipos de Cookies que Utilizamos</h2>
                    
                    <h3 class="text-xl font-semibold text-gray-800 mt-4 mb-3">Cookies Esenciales</h3>
                    <p class="text-gray-700 mb-4">
                        Estas cookies son necesarias para el funcionamiento básico del sitio y no se pueden desactivar:
                    </p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li><strong>Sesión de usuario:</strong> Para mantener tu sesión iniciada</li>
                        <li><strong>Carrito de compras:</strong> Para recordar los cómics en tu carrito</li>
                        <li><strong>Seguridad:</strong> Para proteger contra ataques maliciosos (CSRF)</li>
                        <li><strong>Preferencias de idioma:</strong> Para recordar tu idioma preferido</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-800 mt-4 mb-3">Cookies de Funcionalidad</h3>
                    <p class="text-gray-700 mb-4">
                        Estas cookies mejoran la funcionalidad y personalización del sitio:
                    </p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li><strong>Preferencias de lectura:</strong> Configuraciones de zoom, modo oscuro, etc.</li>
                        <li><strong>Progreso de lectura:</strong> Para recordar dónde dejaste de leer</li>
                        <li><strong>Filtros y búsquedas:</strong> Para recordar tus preferencias de navegación</li>
                        <li><strong>Favoritos:</strong> Para gestionar tu lista de cómics favoritos</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-800 mt-4 mb-3">Cookies de Rendimiento</h3>
                    <p class="text-gray-700 mb-4">
                        Nos ayudan a entender cómo interactúas con ComicNow para mejorar el servicio:
                    </p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li><strong>Análisis de uso:</strong> Páginas visitadas y tiempo de navegación</li>
                        <li><strong>Errores técnicos:</strong> Para identificar y solucionar problemas</li>
                        <li><strong>Optimización:</strong> Para mejorar la velocidad y rendimiento del sitio</li>
                    </ul>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-6 mb-4">3. Cookies de Terceros</h2>
                    <p class="text-gray-700 mb-4">
                        Utilizamos algunos servicios de terceros que pueden establecer sus propias cookies:
                    </p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li><strong>Stripe:</strong> Para procesar pagos de forma segura</li>
                        <li><strong>Servicios de análisis:</strong> Para estadísticas generales de uso</li>
                        <li><strong>CDN (Red de distribución de contenido):</strong> Para mejorar la velocidad de carga</li>
                    </ul>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-6 mb-4">4. Duración de las Cookies</h2>
                    <div class="text-gray-700 mb-4">
                        <p class="mb-2"><strong>Cookies de sesión:</strong> Se eliminan cuando cierras el navegador</p>
                        <p class="mb-2"><strong>Cookies persistentes:</strong> Permanecen entre 30 días y 2 años, dependiendo de su función</p>
                        <p class="mb-2"><strong>Cookies de preferencias:</strong> Hasta 1 año para recordar tus configuraciones</p>
                    </div>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-6 mb-4">5. Control de Cookies</h2>
                    <p class="text-gray-700 mb-4">
                        Tienes varias opciones para controlar las cookies:
                    </p>

                    <h3 class="text-xl font-semibold text-gray-800 mt-4 mb-3">Configuración del Navegador</h3>
                    <p class="text-gray-700 mb-4">
                        Puedes configurar tu navegador para:
                    </p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li>Bloquear todas las cookies</li>
                        <li>Permitir solo cookies de ComicNow</li>
                        <li>Eliminar cookies existentes</li>
                        <li>Recibir una notificación antes de que se establezcan cookies</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-800 mt-4 mb-3">Enlaces de Configuración por Navegador</h3>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li><strong>Chrome:</strong> Configuración > Privacidad y seguridad > Cookies</li>
                        <li><strong>Firefox:</strong> Preferencias > Privacidad y seguridad</li>
                        <li><strong>Safari:</strong> Preferencias > Privacidad</li>
                        <li><strong>Edge:</strong> Configuración > Privacidad, búsqueda y servicios</li>
                    </ul>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-6 mb-4">6. Impacto de Desactivar Cookies</h2>
                    <p class="text-gray-700 mb-4">
                        Si desactivas las cookies, algunas funcionalidades pueden verse afectadas:
                    </p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li>Tendrás que iniciar sesión cada vez que visites el sitio</li>
                        <li>Tu carrito de compras se vaciará al cerrar el navegador</li>
                        <li>Las preferencias de lectura no se guardarán</li>
                        <li>Algunas características personalizadas no funcionarán</li>
                    </ul>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-6 mb-4">7. Cookies y Dispositivos Móviles</h2>
                    <p class="text-gray-700 mb-4">
                        En dispositivos móviles, las cookies funcionan de manera similar. Puedes gestionar las preferencias 
                        de cookies a través de la configuración de tu navegador móvil o aplicación.
                    </p>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-6 mb-4">8. Actualización de esta Política</h2>
                    <p class="text-gray-700 mb-4">
                        Podemos actualizar esta política de cookies ocasionalmente para reflejar cambios en nuestras prácticas 
                        o por otros motivos operativos, legales o reglamentarios.
                    </p>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-6 mb-4">9. Contacto</h2>
                    <p class="text-gray-700 mb-4">
                        Si tienes preguntas sobre nuestra política de cookies, puedes contactarnos:
                    </p>
                    <p class="text-gray-700 mb-4">
                        <strong>Email:</strong> cookies@comicnow.com<br>
                        <strong>Teléfono:</strong> 956 000 000<br>
                        <strong>Horario:</strong> Lunes a Viernes de 10:00 a 20:00
                    </p>

                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-500 text-center">
                            © {{ date('Y') }} ComicNow. Todos los derechos reservados.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 