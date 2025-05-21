// Carrito AJAX handler
document.addEventListener('DOMContentLoaded', function() {
    // Obtener el formulario de añadir al carrito
    const addToCartForm = document.getElementById('add-to-cart-form');
    
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const comicId = this.querySelector('input[name="id_comic"]').value;
            const cantidad = this.querySelector('input[name="cantidad"]').value;
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Cambiar el texto del botón para indicar carga
            submitButton.innerHTML = 'Agregando...';
            submitButton.disabled = true;
            
            // Enviar la solicitud AJAX para agregar al carrito
            fetch('/api/carrito', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    id_comic: comicId,
                    cantidad: cantidad
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar mensaje de éxito
                    const successMessage = document.createElement('div');
                    successMessage.classList.add('bg-green-100', 'border', 'border-green-400', 'text-green-700', 'px-4', 'py-3', 'rounded', 'relative', 'mb-4');
                    successMessage.innerHTML = `
                        <span class="block sm:inline">${data.message}</span>
                    `;
                    
                    // Encontrar el contenedor de mensajes o insertar antes del formulario
                    const messageContainer = document.getElementById('message-container');
                    if (messageContainer) {
                        messageContainer.innerHTML = '';
                        messageContainer.appendChild(successMessage);
                    } else {
                        addToCartForm.parentNode.insertBefore(successMessage, addToCartForm);
                    }
                      // Actualizar los contadores del carrito en la barra de navegación si existen
                    const cartBadge = document.getElementById('cart-badge');
                    const cartBadgeMobile = document.getElementById('cart-badge-mobile');
                    
                    if (cartBadge) {
                        cartBadge.textContent = data.total_items;
                        cartBadge.classList.remove('hidden');
                    }
                    
                    if (cartBadgeMobile) {
                        cartBadgeMobile.textContent = data.total_items;
                        cartBadgeMobile.classList.remove('hidden');
                    }
                    
                    // Añadir una animación al carrito
                    const cartLink = document.querySelector('a[href*="carrito"]');
                    if (cartLink) {
                        cartLink.classList.add('animate-pulse');
                        setTimeout(() => {
                            cartLink.classList.remove('animate-pulse');
                        }, 1500);
                    }
                } else {
                    // Mostrar mensaje de error
                    const errorMessage = document.createElement('div');
                    errorMessage.classList.add('bg-red-100', 'border', 'border-red-400', 'text-red-700', 'px-4', 'py-3', 'rounded', 'relative', 'mb-4');
                    errorMessage.innerHTML = `
                        <span class="block sm:inline">${data.message}</span>
                    `;
                    
                    // Encontrar el contenedor de mensajes o insertar antes del formulario
                    const messageContainer = document.getElementById('message-container');
                    if (messageContainer) {
                        messageContainer.innerHTML = '';
                        messageContainer.appendChild(errorMessage);
                    } else {
                        addToCartForm.parentNode.insertBefore(errorMessage, addToCartForm);
                    }
                }
                
                // Restaurar el texto del botón
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
                
                // Mostrar mensaje de error genérico
                const errorMessage = document.createElement('div');
                errorMessage.classList.add('bg-red-100', 'border', 'border-red-400', 'text-red-700', 'px-4', 'py-3', 'rounded', 'relative', 'mb-4');
                errorMessage.innerHTML = `
                    <span class="block sm:inline">Ocurrió un error al procesar tu solicitud. Por favor, inténtalo nuevamente.</span>
                `;
                
                addToCartForm.parentNode.insertBefore(errorMessage, addToCartForm);
            });
        });
    }
});
