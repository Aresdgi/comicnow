<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comic;
use App\Models\Pedido;
use App\Models\DetallePedido;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CarritoController extends Controller
{
    /**
     * Muestra el contenido del carrito de compras
     */
    public function index()
    {
        $carrito = session()->get('carrito', []);
        $total = 0;
        
        foreach ($carrito as $id => $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        
        return view('carrito.index', [
            'carrito' => $carrito,
            'total' => $total
        ]);
    }
    
    /**
     * Actualiza la cantidad de un ítem en el carrito
     */
    public function actualizar(Request $request, $id_comic)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1'
        ]);
        
        $cantidad = $request->cantidad;
        
        // Verificar que el cómic existe
        $comic = Comic::findOrFail($id_comic);
        
        // Obtener el carrito actual
        $carrito = session()->get('carrito', []);
        
        // Actualizar la cantidad si el producto existe en el carrito
        if (isset($carrito[$id_comic])) {
            $carrito[$id_comic]['cantidad'] = $cantidad;
            session()->put('carrito', $carrito);
            return back()->with('success', 'Cantidad actualizada correctamente.');
        }
        
        return back()->with('error', 'El producto no se encuentra en su carrito.');
    }
    
    /**
     * Elimina un ítem del carrito
     */
    public function eliminar($id)
    {
        $id_comic = $id;
        
        // Obtener el carrito actual
        $carrito = session()->get('carrito', []);
        
        // Eliminar el ítem si existe
        if (isset($carrito[$id_comic])) {
            unset($carrito[$id_comic]);
            session()->put('carrito', $carrito);
            return back()->with('success', 'Producto eliminado del carrito.');
        }
        
        return back()->with('error', 'El producto no se encuentra en su carrito.');
    }
    
    /**
     * Vacía completamente el carrito
     */
    public function vaciar()
    {
        session()->forget('carrito');
        return back()->with('success', 'El carrito ha sido vaciado correctamente.');
    }

    /**
     * API para agregar cómic al carrito vía AJAX.
     */
    public function apiAgregar(Request $request)
    {
        $request->validate([
            'id_comic' => 'required|exists:comics,id_comic',
            'cantidad' => 'required|integer|min:1'
        ]);

        $id = $request->id_comic;
        $cantidad = $request->cantidad;

        // Verificar que el cómic existe
        $comic = Comic::find($id);
        if (!$comic) {
            return response()->json([
                'success' => false,
                'message' => 'El cómic no existe.'
            ]);
        }

        // Obtener el carrito actual
        $carrito = session()->get('carrito', []);

        // Agregar o actualizar la cantidad en el carrito
        if (isset($carrito[$id])) {
            $carrito[$id]['cantidad'] = $carrito[$id]['cantidad'] + $cantidad;
        } else {
            $carrito[$id] = [
                'titulo' => $comic->titulo,
                'precio' => $comic->precio,
                'cantidad' => $cantidad,
                'imagen' => $comic->imagen
            ];
        }

        // Guardar el carrito en la sesión
        session()->put('carrito', $carrito);

        // Obtener el total de items en el carrito
        $totalItems = count($carrito);

        return response()->json([
            'success' => true,
            'message' => 'Comic agregado al carrito exitosamente.',
            'total_items' => $totalItems
        ]);
    }
}