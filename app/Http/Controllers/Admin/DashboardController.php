<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comic;
use App\Models\Pedido;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard principal del administrador
     */
    public function index()
    {
        // Obtener estadísticas de ventas mensuales
        $ventasMensuales = $this->getVentasMensuales();
        
        // Calcular porcentaje de crecimiento en ventas (comparando con mes anterior)
        $porcentajeVentas = $this->getPorcentajeCrecimientoVentas();
        
        // Obtener total de usuarios
        $totalUsuarios = Usuario::count();
        
        // Calcular porcentaje de nuevos usuarios este mes
        $usuariosNuevosPorcentaje = $this->getPorcentajeNuevosUsuarios();
        
        // Obtener cantidad de cómics vendidos este mes
        $comicsVendidos = $this->getComicsVendidos();
        
        // Calcular porcentaje de aumento en ventas de cómics
        $aumentoVentas = $this->getPorcentajeAumentoVentasComics();
        
        // Obtener pedidos recientes (últimos 10)
        $pedidosRecientes = Pedido::with(['usuario', 'detalles'])
            ->orderBy('fecha', 'desc')
            ->take(10)
            ->get();
            
        // Calcular el total de cada pedido sumando los detalles
        foreach ($pedidosRecientes as $pedido) {
            if ($pedido->detalles) {
                $pedido->total = $pedido->detalles->sum(function ($detalle) {
                    return $detalle->precio * $detalle->cantidad;
                });
            } else {
                $pedido->total = 0; // Asignar un valor predeterminado si no hay detalles
            }
        }
        
        // Obtener cómics más populares (basado en ventas)
        $comicsPopulares = Comic::withCount(['detallesPedido as ventas' => function ($query) {
                $query->select(DB::raw('SUM(cantidad)'));
            }])
            ->with('autor')
            ->orderBy('ventas', 'desc')
            ->take(6)
            ->get();
        
        return view('admin.dashboard', compact(
            'ventasMensuales',
            'porcentajeVentas',
            'totalUsuarios',
            'usuariosNuevosPorcentaje',
            'comicsVendidos',
            'aumentoVentas',
            'pedidosRecientes',
            'comicsPopulares'
        ));
    }
    
    /**
     * Calcula las ventas mensuales
     */
    private function getVentasMensuales()
    {
        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();
        
        $ventasMensuales = DB::table('pedidos')
            ->join('detalle_pedidos', 'pedidos.id_pedido', '=', 'detalle_pedidos.id_pedido')
            ->whereBetween('pedidos.fecha', [$inicioMes, $finMes])
            ->sum(DB::raw('detalle_pedidos.precio * detalle_pedidos.cantidad'));
            
        return number_format($ventasMensuales, 2);
    }
    
    /**
     * Calcula el porcentaje de crecimiento en ventas
     */
    private function getPorcentajeCrecimientoVentas()
    {
        $inicioMesActual = Carbon::now()->startOfMonth();
        $finMesActual = Carbon::now()->endOfMonth();
        
        $inicioMesAnterior = Carbon::now()->subMonth()->startOfMonth();
        $finMesAnterior = Carbon::now()->subMonth()->endOfMonth();
        
        $ventasMesActual = DB::table('pedidos')
            ->join('detalle_pedidos', 'pedidos.id_pedido', '=', 'detalle_pedidos.id_pedido')
            ->whereBetween('pedidos.fecha', [$inicioMesActual, $finMesActual])
            ->sum(DB::raw('detalle_pedidos.precio * detalle_pedidos.cantidad'));
            
        $ventasMesAnterior = DB::table('pedidos')
            ->join('detalle_pedidos', 'pedidos.id_pedido', '=', 'detalle_pedidos.id_pedido')
            ->whereBetween('pedidos.fecha', [$inicioMesAnterior, $finMesAnterior])
            ->sum(DB::raw('detalle_pedidos.precio * detalle_pedidos.cantidad'));
            
        // Evitar división por cero
        if ($ventasMesAnterior == 0) {
            return $ventasMesActual > 0 ? 100 : 0;
        }
        
        $porcentaje = (($ventasMesActual - $ventasMesAnterior) / $ventasMesAnterior) * 100;
        return number_format($porcentaje, 1);
    }
    
    /**
     * Calcula el porcentaje de nuevos usuarios este mes
     */
    private function getPorcentajeNuevosUsuarios()
    {
        $inicioMes = Carbon::now()->startOfMonth();
        
        $usuariosNuevos = Usuario::where('created_at', '>=', $inicioMes)->count();
        $totalUsuarios = Usuario::count();
        
        // Evitar división por cero
        if ($totalUsuarios == 0) {
            return 0;
        }
        
        $porcentaje = ($usuariosNuevos / $totalUsuarios) * 100;
        return number_format($porcentaje, 1);
    }
    
    /**
     * Obtiene la cantidad de cómics vendidos este mes
     */
    private function getComicsVendidos()
    {
        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();
        
        $comicsVendidos = DB::table('pedidos')
            ->join('detalle_pedidos', 'pedidos.id_pedido', '=', 'detalle_pedidos.id_pedido')
            ->whereBetween('pedidos.fecha', [$inicioMes, $finMes])
            ->sum('detalle_pedidos.cantidad');
            
        return $comicsVendidos;
    }
    
    /**
     * Calcula el porcentaje de aumento en ventas de cómics
     */
    private function getPorcentajeAumentoVentasComics()
    {
        $inicioMesActual = Carbon::now()->startOfMonth();
        $finMesActual = Carbon::now()->endOfMonth();
        
        $inicioMesAnterior = Carbon::now()->subMonth()->startOfMonth();
        $finMesAnterior = Carbon::now()->subMonth()->endOfMonth();
        
        $comicsVendidosMesActual = DB::table('pedidos')
            ->join('detalle_pedidos', 'pedidos.id_pedido', '=', 'detalle_pedidos.id_pedido')
            ->whereBetween('pedidos.fecha', [$inicioMesActual, $finMesActual])
            ->sum('detalle_pedidos.cantidad');
            
        $comicsVendidosMesAnterior = DB::table('pedidos')
            ->join('detalle_pedidos', 'pedidos.id_pedido', '=', 'detalle_pedidos.id_pedido')
            ->whereBetween('pedidos.fecha', [$inicioMesAnterior, $finMesAnterior])
            ->sum('detalle_pedidos.cantidad');
            
        // Evitar división por cero
        if ($comicsVendidosMesAnterior == 0) {
            return $comicsVendidosMesActual > 0 ? 100 : 0;
        }
        
        $porcentaje = (($comicsVendidosMesActual - $comicsVendidosMesAnterior) / $comicsVendidosMesAnterior) * 100;
        return number_format($porcentaje, 1);
    }
}