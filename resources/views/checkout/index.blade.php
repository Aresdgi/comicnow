<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Resumen de tu pedido</h3>
                
                <div class="mb-8">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cómic
                                    </th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Precio
                                    </th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cantidad
                                    </th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Subtotal
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                <tr>
                                    <td class="py-3 px-4 border-b border-gray-200">
                                        {{ $item['comic']->titulo }}
                                    </td>
                                    <td class="py-3 px-4 border-b border-gray-200">
                                        {{ number_format($item['precio_unitario'], 2) }} €
                                    </td>
                                    <td class="py-3 px-4 border-b border-gray-200">
                                        {{ $item['cantidad'] }}
                                    </td>
                                    <td class="py-3 px-4 border-b border-gray-200">
                                        {{ number_format($item['subtotal'], 2) }} €
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="py-3 px-4 text-right font-bold">
                                        Total:
                                    </td>
                                    <td class="py-3 px-4 font-bold">
                                        {{ number_format($total, 2) }} €
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="mt-8">
                    <h4 class="text-md font-medium text-gray-900 mb-4">Información de pago</h4>
                    
                    <form id="payment-form" method="POST" action="{{ route('cashier.process') }}">
                        @csrf
                        <div class="mb-4">
                            <div id="card-element" class="p-2 border rounded">
                                <!-- Stripe Elements se insertará aquí -->
                            </div>
                            <div id="card-errors" class="text-red-500 text-sm mt-2"></div>
                        </div>
                        
                        <div class="mt-6">
                            <input type="hidden" id="payment_method" name="payment_method">
                            <button type="submit" id="card-button" data-secret="{{ $intent->client_secret }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Pagar {{ number_format($total, 2) }} €
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
      @push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripeKey = '{{ env('STRIPE_KEY') }}';
        const totalAmount = '{{ number_format($total, 2) }}';
    </script>
    <script src="{{ asset('js/cashier-payment.js') }}"></script>
    @endpush
</x-app-layout>
