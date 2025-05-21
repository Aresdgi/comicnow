<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('¡Pago completado!') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 text-center">
                <div class="mb-8">
                    <div class="text-green-500 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">¡Gracias por tu compra!</h3>
                    <p class="text-gray-600 mb-4">Tu pedido ha sido procesado correctamente.</p>
                    
                    <div class="bg-gray-100 p-4 rounded-lg inline-block">
                        <p class="text-gray-800">Número de pedido: <span class="font-bold">{{ $pedido->id_pedido }}</span></p>
                        <p class="text-gray-800">Total: <span class="font-bold">{{ number_format($pedido->total, 2) }} €</span></p>
                    </div>
                </div>
                
                <div class="mt-8">
                    <p class="text-gray-600 mb-6">Puedes encontrar los cómics comprados en tu biblioteca personal.</p>
                    
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('biblioteca.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Ir a mi biblioteca
                        </a>
                        
                        <a href="{{ route('pedidos.show', $pedido->id_pedido) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Ver detalles del pedido
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
