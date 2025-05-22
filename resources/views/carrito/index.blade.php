<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Carrito de Compras') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif                @if(count($carrito) > 0)
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
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($carrito as $id => $item)
                                <tr>
                                    <td class="py-3 px-4 border-b border-gray-200">
                                        {{ $item['titulo'] }}
                                    </td>
                                    <td class="py-3 px-4 border-b border-gray-200">
                                        {{ number_format($item['precio'], 2) }} €
                                    </td>
                                    <td class="py-3 px-4 border-b border-gray-200">
                                        <form action="{{ route('carrito.update', $id) }}" method="POST" class="flex items-center">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="cantidad" value="{{ $item['cantidad'] }}" min="1" max="10" class="w-16 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                            <button type="submit" class="ml-2 text-sm text-blue-600 hover:text-blue-800">
                                                Actualizar
                                            </button>
                                        </form>
                                    </td>
                                    <td class="py-3 px-4 border-b border-gray-200">
                                        {{ number_format($item['precio'] * $item['cantidad'], 2) }} €
                                    </td>
                                    <td class="py-3 px-4 border-b border-gray-200">
                                        <form action="{{ route('carrito.remove', $id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="py-3 px-4 text-right font-bold">
                                        Total:
                                    </td>
                                    <td class="py-3 px-4 font-bold">
                                        {{ number_format($total, 2) }} €
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <form action="{{ route('carrito.vaciar') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Vaciar carrito
                            </button>
                        </form>

                        <a href="{{ route('checkout') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Proceder al pago
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-lg text-gray-600">Tu carrito está vacío</p>
                        <a href="{{ route('comics.index') }}" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Ver cómics
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
