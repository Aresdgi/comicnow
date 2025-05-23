// Manejador de pagos de caja para Stripe
document.addEventListener('DOMContentLoaded', function() {
    const stripe = Stripe(stripeKey);
    const elements = stripe.elements();
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontFamily: '"Segoe UI", "Helvetica Neue", Arial, sans-serif',
                fontSize: '16px',
                color: '#32325d',
                '::placeholder': {
                    color: '#aab7c4',
                },
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a',
            },
        },
    });
    
    cardElement.mount('#card-element');
    
    // Manejar el envío del formulario
    const form = document.getElementById('payment-form');
    const cardButton = document.getElementById('card-button');
    const cardErrors = document.getElementById('card-errors');
    
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        
        // Deshabilitar el botón de envío para prevenir múltiples clics
        cardButton.disabled = true;
        cardButton.classList.add('opacity-75', 'cursor-not-allowed');
        cardButton.textContent = 'Procesando...';
        
        try {
            const { paymentMethod, error } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
            });
            
            if (error) {
                // Mostrar mensaje de error
                cardErrors.textContent = error.message;
                cardButton.disabled = false;
                cardButton.classList.remove('opacity-75', 'cursor-not-allowed');
                cardButton.textContent = `Pagar ${totalAmount} €`;
            } else {
                // Agregar ID del método de pago al formulario y enviar
                const hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'payment_method');
                hiddenInput.setAttribute('value', paymentMethod.id);
                form.appendChild(hiddenInput);
                
                form.submit();
            }
        } catch (err) {
            cardErrors.textContent = "Ocurrió un error al procesar el pago. Por favor, intenta nuevamente.";
            cardButton.disabled = false;
            cardButton.classList.remove('opacity-75', 'cursor-not-allowed');
            cardButton.textContent = `Pagar ${totalAmount} €`;
            console.error('Error:', err);
        }
    });
});
