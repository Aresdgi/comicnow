<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Comic;
use App\Models\Usuario;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
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
        $comics = Comic::where('stock', '>', 0)->get();
        return view('pedidos.create', compact('usuarios', 'comics'));
    }

    /**
     * Almacena un pedido recién creado en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'metodo_pago' => 'required|string|max:100',
            'comics' => 'required|array',
            'comics.*.id_comic' => 'required|exists:comics,id_comic',
            'comics.*.cantidad' => 'required|integer|min:1',
        ]);

        // Validar stock disponible
        foreach ($request->comics as $item) {
            $comic = Comic::find($item['id_comic']);
            if ($comic->stock < $item['cantidad']) {
                return back()->with('error', "No hay suficiente stock para {$comic->titulo}");
            }
        }

        // Crear pedido
        $pedido = Pedido::create([
            'id_usuario' => $request->id_usuario,
            'fecha' => now(),
            'estado' => 'pendiente',
            'metodo_pago' => $request->metodo_pago,
        ]);

        // Crear detalles del pedido
        foreach ($request->comics as $item) {
            $comic = Comic::find($item['id_comic']);
            
            DetallePedido::create([
                'id_pedido' => $pedido->id_pedido,
                'id_comic' => $item['id_comic'],
                'precio' => $comic->precio,
                'cantidad' => $item['cantidad'],
            ]);

            // Actualizar stock
            $comic->stock -= $item['cantidad'];
            $comic->save();
        }

        return redirect()->route('pedidos.index')
            ->with('success', 'Pedido creado exitosamente');
    }

    /**
     * Muestra el pedido especificado.
     */
    public function show(Pedido $pedido)
    {
        $pedido->load('detalles.comic', 'usuario');
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
        // Primero devolvemos el stock
        foreach ($pedido->detalles as $detalle) {
            $comic = Comic::find($detalle->id_comic);
            if ($comic) {
                $comic->stock += $detalle->cantidad;
                $comic->save();
            }
        }

        // Eliminar detalles (se podría hacer con un cascade en la migración también)
        DetallePedido::where('id_pedido', $pedido->id_pedido)->delete();
        
        // Eliminar pedido
        $pedido->delete();

        return redirect()->route('pedidos.index')
            ->with('success', 'Pedido eliminado exitosamente');
    }
}