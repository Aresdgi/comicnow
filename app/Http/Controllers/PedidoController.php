<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Comic;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PedidoController extends Controller
{
    /**
     * Constructor para aplicar middleware de autenticación
     */
    public function __construct()
    {
        // La autenticación se implementará en las rutas
    }

    /**
     * Muestra una lista de todos los pedidos.
     */
    public function index()
    {
        $pedidos = Pedido::with('usuario')->get();
        return view('pedidos.index', compact('pedidos'));
    }

    /**
     * Muestra el formulario para crear un nuevo pedido.
     */
    public function create()
    {
        $usuarios = Usuario::all();
        $comics = Comic::all();
        return view('pedidos.create', compact('usuarios', 'comics'));
    }

    /**
     * Muestra el formulario de checkout para finalizar la compra
     */
    public function checkout()
    {
        $carrito = Session::get('carrito', []);
        
        // Redireccionar si el carrito está vacío
        if (empty($carrito)) {
            return redirect()->route('carrito.index')->with('error', 'Su carrito está vacío. Agregue productos antes de realizar el checkout.');
        }
        
        $items = [];
        $total = 0;
        
        foreach ($carrito as $id_comic => $cantidad) {
            $comic = Comic::find($id_comic);
            if ($comic) {
                $subtotal = $comic->precio * $cantidad;
                $total += $subtotal;
                $items[] = [
                    'comic' => $comic,
                    'cantidad' => $cantidad,
                    'subtotal' => $subtotal
                ];
            }
        }
        
        return view('pedidos.checkout', compact('items', 'total'));
    }

    /**
     * Almacena un pedido recién creado en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'direccion_envio' => 'required|string',
            'metodo_pago' => 'required|in:tarjeta,paypal,transferencia',
        ]);
        
        $carrito = Session::get('carrito', []);
        
        // Verificar si el carrito está vacío
        if (empty($carrito)) {
            return redirect()->route('carrito.index')->with('error', 'Su carrito está vacío. No se puede procesar un pedido vacío.');
        }
        
        // Iniciar una transacción de base de datos
        DB::beginTransaction();
        
        try {
            $total = 0;
            
            // Calcular el total del pedido
            foreach ($carrito as $id_comic => $cantidad) {
                $comic = Comic::find($id_comic);
                if ($comic) {
                    $total += $comic->precio * $cantidad;
                }
            }
            
            // Crear el pedido
            $pedido = new Pedido();
            $pedido->id_usuario = Auth::id();
            $pedido->fecha_pedido = Carbon::now();
            $pedido->estado = 'pendiente';
            $pedido->total = $total;
            $pedido->direccion_envio = $request->direccion_envio;
            $pedido->metodo_pago = $request->metodo_pago;
            $pedido->save();
            
            // Crear los detalles del pedido
            foreach ($carrito as $id_comic => $cantidad) {
                $comic = Comic::find($id_comic);
                if ($comic) {
                    // Crear detalle
                    $detalle = new DetallePedido();
                    $detalle->id_pedido = $pedido->id_pedido;
                    $detalle->id_comic = $id_comic;
                    $detalle->cantidad = $cantidad;
                    $detalle->precio_unitario = $comic->precio;
                    $detalle->save();
                }
            }
            
            // Vaciar el carrito
            Session::forget('carrito');
            
            // Confirmar transacción
            DB::commit();
            
            return redirect()->route('pedidos.confirmacion', $pedido->id_pedido)->with('success', 'Su pedido ha sido procesado exitosamente.');
            
        } catch (\Exception $e) {
            // Revertir en caso de error
            DB::rollBack();
            return redirect()->route('carrito.index')->with('error', 'Ocurrió un error al procesar tu pago: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el pedido especificado.
     */
    public function show($id)
    {
        $pedido = Pedido::with(['detallesPedido.comic', 'usuario'])
                ->where('id_usuario', Auth::id())
                ->findOrFail($id);
                
        return view('pedidos.show', compact('pedido'));
    }

    /**
     * Muestra el formulario para editar el pedido especificado.
     */
    public function edit(Pedido $pedido)
    {
        $usuarios = Usuario::all();
        $pedido->load('detalles.comic');
        return view('pedidos.edit', compact('pedido', 'usuarios'));
    }

    /**
     * Actualiza el pedido especificado en la base de datos.
     */
    public function update(Request $request, Pedido $pedido)
    {
        $request->validate([
            'estado' => 'required|string|max:100',
            'metodo_pago' => 'required|string|max:100',
        ]);

        $pedido->update([
            'estado' => $request->estado,
            'metodo_pago' => $request->metodo_pago,
        ]);

        return redirect()->route('pedidos.index')
            ->with('success', 'Estado del pedido actualizado exitosamente');
    }

    /**
     * Elimina el pedido especificado de la base de datos.
     */
    public function destroy(Pedido $pedido)
    {
        // Eliminar detalles (se podría hacer con un cascade en la migración también)
        DetallePedido::where('id_pedido', $pedido->id_pedido)->delete();
        
        // Eliminar pedido
        $pedido->delete();

        return redirect()->route('pedidos.index')
            ->with('success', 'Pedido eliminado exitosamente');
    }

    /**
     * Muestra la página de confirmación de pedido
     */
    public function confirmacion($id)
    {
        $pedido = Pedido::with(['detallesPedido.comic', 'usuario'])
                ->where('id_usuario', Auth::id())
                ->findOrFail($id);
                
        return view('pedidos.confirmacion', compact('pedido'));
    }
    
    /**
     * Muestra un listado de los pedidos del usuario autenticado
     */
    public function misPedidos()
    {
        $pedidos = Pedido::where('id_usuario', Auth::id())
                 ->orderByDesc('fecha_pedido')
                 ->paginate(10);
                 
        return view('pedidos.mis-pedidos', compact('pedidos'));
    }
    
    /**
     * Permite al usuario cancelar un pedido (solo si está en estado pendiente)
     */
    public function cancelar($id)
    {
        $pedido = Pedido::where('id_usuario', Auth::id())
                ->where('estado', 'pendiente')
                ->findOrFail($id);
        
        DB::beginTransaction();
        
        try {
            // Actualizar el estado del pedido
            $pedido->estado = 'cancelado';
            $pedido->save();
            
            DB::commit();
            
            return redirect()->route('pedidos.mis-pedidos')
                ->with('success', 'Su pedido ha sido cancelado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pedidos.mis-pedidos')
                ->with('error', 'Ha ocurrido un error al cancelar su pedido. Por favor, inténtelo nuevamente.');
        }
    }
}