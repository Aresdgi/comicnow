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
     * Agrega un cómic al carrito o incrementa su cantidad
     */
    public function agregar(Request $request)
    {
        $request->validate([
            'id_comic' => 'required|exists:comics,id_comic',
            'cantidad' => 'required|integer|min:1'
        ]);
        
        $id = $request->id_comic;
        $cantidad = $request->cantidad;
        
        // Verificar stock disponible
        $comic = Comic::findOrFail($id);
        
        if ($comic->stock < $cantidad) {
            return back()->with('error', 'Lo sentimos, no hay suficiente stock disponible. Stock actual: ' . $comic->stock);
        }
        
        // Obtener el carrito actual
        $carrito = session()->get('carrito', []);
        
        // Agregar el producto o actualizar la cantidad
        if (isset($carrito[$id])) {
            $nuevaCantidad = $carrito[$id]['cantidad'] + $cantidad;
            
            // Verificar que la nueva cantidad no exceda el stock
            if ($nuevaCantidad > $comic->stock) {
                return back()->with('error', 'La cantidad solicitada excede el stock disponible. Stock actual: ' . $comic->stock);
            }
            
            $carrito[$id]['cantidad'] = $nuevaCantidad;
        } else {
            $carrito[$id] = [
                'titulo' => $comic->titulo,
                'precio' => $comic->precio,
                'cantidad' => $cantidad,
                'imagen' => $comic->imagen
            ];
        }
        
        // Guardar el carrito actualizado en la sesión
        session()->put('carrito', $carrito);
        
        return back()->with('success', 'El cómic "' . $comic->titulo . '" ha sido agregado a su carrito.');
    }
    
    /**
     * Actualiza la cantidad de un ítem en el carrito
     */
    public function actualizar(Request $request)
    {
        $request->validate([
            'id_comic' => 'required|exists:comics,id_comic',
            'cantidad' => 'required|integer|min:1'
        ]);
        
        $id_comic = $request->id_comic;
        $cantidad = $request->cantidad;
        
        // Verificar stock disponible
        $comic = Comic::findOrFail($id_comic);
        
        if ($comic->stock < $cantidad) {
            return back()->with('error', 'Lo sentimos, no hay suficiente stock disponible. Stock actual: ' . $comic->stock);
        }
        
        // Obtener el carrito actual
        $carrito = Session::get('carrito', []);
        
        // Actualizar la cantidad si el producto existe en el carrito
        if (isset($carrito[$id_comic])) {
            $carrito[$id_comic] = $cantidad;
            Session::put('carrito', $carrito);
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
        $carrito = Session::get('carrito', []);
        
        // Eliminar el ítem si existe
        if (isset($carrito[$id_comic])) {
            unset($carrito[$id_comic]);
            Session::put('carrito', $carrito);
            return back()->with('success', 'Producto eliminado del carrito.');
        }
        
        return back()->with('error', 'El producto no se encuentra en su carrito.');
    }
    
    /**
     * Vacía completamente el carrito
     */
    public function vaciar()
    {
        Session::forget('carrito');
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

        $id_comic = $request->id_comic;
        $cantidad = $request->cantidad;

        // Verificar que hay suficiente stock
        $comic = Comic::find($id_comic);
        if (!$comic || $comic->stock < $cantidad) {
            return response()->json([
                'success' => false,
                'message' => 'No hay suficiente stock disponible.'
            ]);
        }

        // Obtener el carrito actual
        $carrito = Session::get('carrito', []);

        // Agregar o actualizar la cantidad en el carrito
        if (isset($carrito[$id_comic])) {
            $carrito[$id_comic] += $cantidad;
        } else {
            $carrito[$id_comic] = $cantidad;
        }

        // Verificar nuevamente el stock total
        if ($comic->stock < $carrito[$id_comic]) {
            return response()->json([
                'success' => false,
                'message' => 'No hay suficiente stock disponible para la cantidad solicitada.'
            ]);
        }

        // Guardar el carrito en la sesión
        Session::put('carrito', $carrito);

        // Obtener el total de items en el carrito
        $totalItems = array_sum($carrito);

        return response()->json([
            'success' => true,
            'message' => 'Comic agregado al carrito exitosamente.',
            'total_items' => $totalItems
        ]);
    }
}