<x-app-layout>
    <div class="min-h-screen bg-gray-100 py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-8 prose prose-lg max-w-none">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Política de Privacidad</h1>
                    
                    <p class="text-gray-600 mb-4">
                        <strong>Última actualización:</strong> {{ date('d/m/Y') }}
                    </p>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-6 mb-4">1. Información que Recopilamos</h2>
                    <p class="text-gray-700 mb-4">
                        En ComicNow recopilamos únicamente la información necesaria para proporcionarte nuestros servicios:
                    </p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li>Nombre de usuario y dirección de correo electrónico</li>
                        <li>Información de perfil que decidas compartir</li>
                        <li>Historial de lectura y preferencias de cómics</li>
                        <li>Datos de uso de la plataforma para mejorar el servicio</li>
                    </ul>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-6 mb-4">2. Uso de la Información</h2>
                    <p class="text-gray-700 mb-4">
                        Utilizamos tu información para:
                    </p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li>Proporcionar y mantener nuestros servicios</li>
                        <li>Personalizar tu experiencia de lectura</li>
                        <li>Comunicarnos contigo sobre actualizaciones y nuevos contenidos</li>
                        <li>Mejorar la funcionalidad de la plataforma</li>
                    </ul>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-6 mb-4">3. Protección de Datos</h2>
                    <p class="text-gray-700 mb-4">
                        Nos comprometemos a proteger tu información personal mediante medidas de seguridad apropiadas. 
                        Tus datos están almacenados de forma segura y no los compartimos con terceros sin tu consentimiento.
                    </p>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-6 mb-4">4. Cookies</h2>
                    <p class="text-gray-700 mb-4">
                        Utilizamos cookies esenciales para el funcionamiento del sitio y para recordar tus preferencias. 
                        Puedes desactivar las cookies en tu navegador, aunque esto puede afectar algunas funcionalidades.
                    </p>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-6 mb-4">5. Tus Derechos</h2>
                    <p class="text-gray-700 mb-4">
                        Tienes derecho a:
                    </p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li>Acceder a tu información personal</li>
                        <li>Corregir datos incorrectos</li>
                        <li>Solicitar la eliminación de tu cuenta</li>
                        <li>Exportar tus datos</li>
                    </ul>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-6 mb-4">6. Contacto</h2>
                    <p class="text-gray-700 mb-4">
                        Si tienes alguna pregunta sobre esta política de privacidad, puedes contactarnos en:
                    </p>
                    <p class="text-gray-700 mb-4">
                        <strong>Email:</strong> privacidad@comicnow.com
                    </p>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-6 mb-4">7. Cambios en la Política</h2>
                    <p class="text-gray-700 mb-4">
                        Nos reservamos el derecho de actualizar esta política de privacidad ocasionalmente. 
                        Te notificaremos sobre cambios importantes por correo electrónico o mediante un aviso en la plataforma.
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
