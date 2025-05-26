@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('pedidos.index') }}" class="text-blue-500 hover:text-blue-700 mb-4 inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Volver a mis pedidos
        </a>
        <h1 class="text-3xl font-bold">Detalles del Pedido #{{ $pedido->id_pedido }}</h1>
    </div>
    
    <div class="bg-white shadow-md rounded-lg p-6">
        <!-- Información del pedido -->
        <div class="mb-6 pb-6 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Fecha del pedido</h3>
                    <p class="mt-1 text-lg text-gray-900">{{ \Carbon\Carbon::parse($pedido->fecha)->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Estado</h3>
                    <p class="mt-1 text-lg">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $pedido->estado === 'pagado' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($pedido->estado) }}
                        </span>
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Método de pago</h3>
                    <p class="mt-1 text-lg text-gray-900">{{ ucfirst(str_replace('_', ' ', $pedido->metodo_pago)) }}</p>
                </div>
            </div>
        </div>
        
        <!-- Detalles de los cómics -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4">Cómics comprados</h3>
            <div class="space-y-4">
                @foreach($pedido->detalles as $detalle)
                    <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                        @if($detalle->comic && $detalle->comic->imagen)
                            <img src="{{ asset('storage/' . $detalle->comic->imagen) }}" 
                                 alt="{{ $detalle->comic->titulo }}" 
                                 class="w-16 h-16 object-cover rounded">
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">{{ $detalle->comic->titulo ?? 'Cómic no disponible' }}</h4>
                            @if($detalle->comic && $detalle->comic->autor)
                                <p class="text-sm text-gray-600">por {{ $detalle->comic->autor->nombre }}</p>
                            @endif
                            <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                <span>Cantidad: {{ $detalle->cantidad }}</span>
                                <span>Precio unitario: {{ number_format($detalle->precio, 2) }}€</span>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <p class="text-lg font-medium text-gray-900">{{ number_format($detalle->precio * $detalle->cantidad, 2) }}€</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- Total del pedido -->
        <div class="pt-6 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    @if($pedido->payment_id)
                        <p class="text-sm text-gray-500">ID de pago: {{ $pedido->payment_id }}</p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-green-600">Total: {{ number_format($pedido->total, 2) }}€</p>
                </div>
            </div>
        </div>
        
        <!-- Acciones -->
        <div class="mt-6 pt-6 border-t border-gray-200 flex justify-between">
            <a href="{{ route('biblioteca.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Ir a mi biblioteca
            </a>
            
            <a href="{{ route('comics.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                Seguir comprando
            </a>
        </div>
    </div>
</div>
@endsection 