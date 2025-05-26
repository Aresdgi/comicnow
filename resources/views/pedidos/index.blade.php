@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Mis Pedidos</h1>
    
    @if($pedidos->count() > 0)
        <div class="space-y-4">
            @foreach($pedidos as $pedido)
                <div class="bg-white shadow-md rounded-lg p-6 border-l-4 
                    @if($pedido->estado == 'completado') border-green-500
                    @elseif($pedido->estado == 'pendiente') border-yellow-500
                    @else border-gray-500 @endif">
                    
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-semibold">Pedido #{{ $pedido->id_pedido }}</h3>
                            <p class="text-gray-600">{{ \Carbon\Carbon::parse($pedido->fecha)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                                @if($pedido->estado == 'completado') bg-green-100 text-green-800
                                @elseif($pedido->estado == 'pendiente') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($pedido->estado) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h4 class="font-medium mb-2">Cómics comprados:</h4>
                        <div class="space-y-2">
                            @foreach($pedido->detalles as $detalle)
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <div class="flex items-center space-x-3">
                                        @if($detalle->comic && $detalle->comic->imagen)
                                            <img src="{{ asset('storage/' . $detalle->comic->imagen) }}" 
                                                 alt="{{ $detalle->comic->titulo }}" 
                                                 class="w-12 h-12 object-cover rounded">
                                        @endif
                                        <div>
                                            <p class="font-medium">{{ $detalle->comic->titulo ?? 'Cómic no disponible' }}</p>
                                            <p class="text-sm text-gray-600">Cantidad: {{ $detalle->cantidad }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium">{{ number_format($detalle->precio * $detalle->cantidad, 2) }}€</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <div>
                            <p class="text-sm text-gray-600">Método de pago: <span class="font-medium">{{ ucfirst($pedido->metodo_pago) }}</span></p>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-bold text-green-600">Total: {{ number_format($pedido->total, 2) }}€</p>
                        </div>
                    </div>
                    

                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white shadow-md rounded-lg p-8 text-center">
            <div class="mb-4">
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-medium text-gray-900 mb-2">No tienes pedidos aún</h3>
            <p class="text-gray-600 mb-6">¡Comienza a explorar nuestro catálogo de cómics y realiza tu primera compra!</p>
            <a href="{{ route('comics.index') }}" 
               class="inline-block px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                Explorar Cómics
            </a>
        </div>
    @endif
</div>
@endsection