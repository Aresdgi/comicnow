<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComicController;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\BibliotecaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ResenaController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\AdminController;

// Rutas públicas
Route::get('/', [ComicController::class, 'destacados'])->name('home');
Route::get('/comics', [ComicController::class, 'index'])->name('comics.index');
Route::get('/comics/{id}', [ComicController::class, 'show'])->name('comics.show');
Route::get('/autores', [AutorController::class, 'index'])->name('autores.index');
Route::get('/autores/{id}', [AutorController::class, 'show'])->name('autores.show');
Route::get('/buscar', [ComicController::class, 'buscar'])->name('comics.buscar');
Route::get('/contacto', function () { return view('contacto'); })->name('contacto');
Route::get('/sobre-nosotros', function () { return view('about'); })->name('about');

// Rutas para usuarios autenticados
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    // Dashboard de Jetstream
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Perfil de usuario
    Route::get('/perfil', [UsuarioController::class, 'perfil'])->name('usuario.perfil');
    Route::put('/perfil', [UsuarioController::class, 'actualizar'])->name('usuario.actualizar');
    
    // Biblioteca del usuario
    Route::get('/biblioteca', [BibliotecaController::class, 'index'])->name('biblioteca.index');
    Route::get('/biblioteca/{id}', [BibliotecaController::class, 'leer'])->name('biblioteca.leer');
    
    // Carrito de compras
    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
    Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::delete('/carrito/eliminar/{id}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
    Route::delete('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');
    
    // Proceso de compra
    Route::get('/checkout', [PedidoController::class, 'checkout'])->name('pedido.checkout');
    Route::post('/checkout/procesar', [PedidoController::class, 'procesar'])->name('pedido.procesar');
    
    // Historial de pedidos
    Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/{id}', [PedidoController::class, 'show'])->name('pedidos.show');
    
    // Reseñas
    Route::post('/resenas/crear/{comic_id}', [ResenaController::class, 'store'])->name('resenas.store');
    Route::delete('/resenas/{id}', [ResenaController::class, 'destroy'])->name('resenas.destroy');
});

// Rutas de administración
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Gestión de cómics
    Route::get('/comics', [AdminController::class, 'comics'])->name('admin.comics');
    Route::get('/comics/crear', [AdminController::class, 'comicCrear'])->name('admin.comics.crear');
    Route::post('/comics', [AdminController::class, 'comicStore'])->name('admin.comics.store');
    Route::get('/comics/editar/{id}', [AdminController::class, 'comicEditar'])->name('admin.comics.editar');
    Route::put('/comics/{id}', [AdminController::class, 'comicUpdate'])->name('admin.comics.update');
    Route::delete('/comics/{id}', [AdminController::class, 'comicDestroy'])->name('admin.comics.destroy');
    
    // Gestión de autores
    Route::get('/autores', [AdminController::class, 'autores'])->name('admin.autores');
    Route::get('/autores/crear', [AdminController::class, 'autorCrear'])->name('admin.autores.crear');
    Route::post('/autores', [AdminController::class, 'autorStore'])->name('admin.autores.store');
    Route::get('/autores/editar/{id}', [AdminController::class, 'autorEditar'])->name('admin.autores.editar');
    Route::put('/autores/{id}', [AdminController::class, 'autorUpdate'])->name('admin.autores.update');
    Route::delete('/autores/{id}', [AdminController::class, 'autorDestroy'])->name('admin.autores.destroy');
    
    // Gestión de usuarios
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::get('/usuarios/editar/{id}', [AdminController::class, 'usuarioEditar'])->name('admin.usuarios.editar');
    Route::put('/usuarios/{id}', [AdminController::class, 'usuarioUpdate'])->name('admin.usuarios.update');
    Route::delete('/usuarios/{id}', [AdminController::class, 'usuarioDestroy'])->name('admin.usuarios.destroy');
    
    // Gestión de pedidos
    Route::get('/pedidos', [AdminController::class, 'pedidos'])->name('admin.pedidos');
    Route::get('/pedidos/{id}', [AdminController::class, 'pedidoShow'])->name('admin.pedidos.show');
    Route::put('/pedidos/{id}/estado', [AdminController::class, 'pedidoActualizarEstado'])->name('admin.pedidos.estado');
    
    // Estadísticas
    Route::get('/estadisticas', [AdminController::class, 'estadisticas'])->name('admin.estadisticas');
});

// API para interacciones AJAX
Route::prefix('api')->middleware('auth')->group(function () {
    Route::get('/comics', [ComicController::class, 'api'])->name('api.comics');
    Route::get('/comics/{id}', [ComicController::class, 'apiShow'])->name('api.comic');
    Route::get('/autores', [AutorController::class, 'api'])->name('api.autores');
    Route::post('/carrito', [CarritoController::class, 'apiAgregar'])->name('api.carrito');
    Route::get('/busqueda', [ComicController::class, 'apiBusqueda'])->name('api.busqueda');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
