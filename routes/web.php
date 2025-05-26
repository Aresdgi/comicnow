<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComicController;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\BibliotecaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ResenaController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Admin\ComicController as AdminComicController;
// use App\Http\Controllers\Admin\PedidoController as AdminPedidoController;
// use App\Http\Controllers\Admin\AutorController as AdminAutorController;
// use App\Http\Controllers\Admin\ResenaController as AdminResenaController;

// Rutas públicas
Route::get('/', function () {
    return view('home');
})->name('home');
Route::get('/comics', [ComicController::class, 'index'])->name('comics.index');
Route::get('/comics/{comic}', [ComicController::class, 'show'])->name('comics.show');
Route::get('/buscar', [ComicController::class, 'buscar'])->name('comics.buscar');
Route::get('/contacto', function () { return view('contacto'); })->name('contacto');
Route::get('/sobre-nosotros', function () { return view('about'); })->name('about');

// Configuración de Cashier para Stripe
Route::post('/stripe/webhook', [\Laravel\Cashier\Http\Controllers\WebhookController::class, 'handleWebhook'])->name('cashier.webhook');

// Rutas para usuarios autenticados
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    // Dashboard principal - redirecciona según el rol
    Route::get('/dashboard', function () {
        if(auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
    })->name('dashboard');
    
    // Perfil de usuario
    Route::get('/perfil', [UsuarioController::class, 'perfil'])->name('usuario.perfil');
    Route::put('/perfil', [UsuarioController::class, 'actualizar'])->name('usuario.actualizar');
    
    // Biblioteca del usuario
    Route::get('/biblioteca', [BibliotecaController::class, 'index'])->name('biblioteca.index');
    Route::get('/biblioteca/{id_comic}', [BibliotecaController::class, 'leer'])->name('biblioteca.leer');
    Route::delete('/biblioteca/{id_comic}', [BibliotecaController::class, 'destroy'])->name('biblioteca.eliminar');
    
    // Carrito de compras
    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
    Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::put('/carrito/actualizar/{id_comic}', [CarritoController::class, 'actualizar'])->name('carrito.update');
    Route::delete('/carrito/eliminar/{id}', [CarritoController::class, 'eliminar'])->name('carrito.remove');
    Route::delete('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');
    
    // Cashier checkout
    Route::get('/checkout', [App\Http\Controllers\CashierController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/process', [App\Http\Controllers\CashierController::class, 'process'])->name('cashier.process');
    Route::get('/checkout/success', [App\Http\Controllers\CashierController::class, 'success'])->name('checkout.success');
    
    // Historial de pedidos
    Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/{id}', [PedidoController::class, 'show'])->name('pedidos.show');
    
    // Reseñas
    Route::post('/resenas/crear/{comic_id}', [ResenaController::class, 'store'])->name('resenas.store');
    Route::post('/resenas/lector/{comic_id}', [ResenaController::class, 'storeFromReader'])->name('resenas.store.reader');
    Route::delete('/resenas/{id}', [ResenaController::class, 'destroy'])->name('resenas.destroy');
});

// Rutas de administración unificadas
Route::prefix('admin')->name('admin.')->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // Rutas para gestión de cómics
    Route::resource('comics', AdminComicController::class);
    
    // Rutas para gestión de autores
    Route::resource('autores', AutorController::class)->parameters([
        'autores' => 'autor'
    ]);
    
    // Rutas para gestión de usuarios (solo listado)
    Route::get('users', [UsuarioController::class, 'index'])->name('users.index');
    
    
});

// Rutas para usuarios normales
Route::prefix('user')->name('user.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    
    // Rutas para reseñas
    Route::get('/resenas', [App\Http\Controllers\User\ResenaController::class, 'index'])->name('resenas');
    Route::get('/resenas/{id_resena}/edit', [App\Http\Controllers\User\ResenaController::class, 'edit'])->name('resenas.edit');
    Route::put('/resenas/{id_resena}', [App\Http\Controllers\User\ResenaController::class, 'update'])->name('resenas.update');
    Route::delete('/resenas/{id_resena}', [App\Http\Controllers\User\ResenaController::class, 'destroy'])->name('resenas.destroy');
});

// API para interacciones AJAX
Route::prefix('api')->middleware('auth')->group(function () {
    Route::get('/comics', [ComicController::class, 'api'])->name('api.comics');
    Route::get('/comics/{id}', [ComicController::class, 'apiShow'])->name('api.comic');
    Route::get('/autores', [AutorController::class, 'api'])->name('api.autores');
    Route::post('/carrito', [CarritoController::class, 'apiAgregar'])->name('api.carrito');
    Route::get('/busqueda', [ComicController::class, 'apiBusqueda'])->name('api.busqueda');
});
