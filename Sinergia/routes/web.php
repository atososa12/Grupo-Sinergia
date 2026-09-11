<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfiguracionNegocioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegistroNegocioController;
use App\Http\Controllers\TiendaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/comercio/{idComercio}', [TiendaController::class, 'show'])->name('comercio.show');
Route::post('/comercio/{idComercio}/pedido', [TiendaController::class, 'storePedido'])->name('comercio.pedido');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [RegistroNegocioController::class, 'create'])->name('register');
    Route::post('/register', [RegistroNegocioController::class, 'store'])->name('register.submit');
    Route::get('/registro-negocio', [RegistroNegocioController::class, 'create'])->name('registro.negocio');
    Route::post('/registro-negocio', [RegistroNegocioController::class, 'store'])->name('registro.negocio.submit');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/productos', [DashboardController::class, 'storeProducto'])->name('dashboard.producto.store');
    Route::post('/dashboard/pedidos/{pedido}/estado', [DashboardController::class, 'updatePedidoEstado'])->name('dashboard.pedido.estado');
    Route::post('/dashboard/tipos-precio', [ConfiguracionNegocioController::class, 'crearTipoPrecio'])->name('dashboard.tipos.precio.store');
    Route::post('/dashboard/tipos-entrega', [ConfiguracionNegocioController::class, 'crearTipoEntrega'])->name('dashboard.tipos.entrega.store');
    Route::post('/dashboard/metodos-pago', [ConfiguracionNegocioController::class, 'crearMetodoPago'])->name('dashboard.metodos.pago.store');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
