<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfiguracionNegocioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegistroNegocioController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\ClienteAuthController;
use App\Http\Controllers\ClienteNotificacionController;
use Illuminate\Support\Facades\Route;

// Página principal
Route::get('/', function () {
    return view('welcome');
});

// Comercios
Route::get('/comercios', [TiendaController::class, 'comercios'])
    ->name('comercios.index');

Route::get('/comercio/{idComercio}', [TiendaController::class, 'show'])
    ->name('comercio.show');

// Realizar pedido
Route::post('/comercio/{idComercio}/pedido', [TiendaController::class, 'storePedido'])
    ->name('comercio.pedido');

// Autenticación de comercios
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit');

    Route::get('/register', [RegistroNegocioController::class, 'create'])
        ->name('register');

    Route::post('/register', [RegistroNegocioController::class, 'store'])
        ->name('register.submit');

    Route::get('/registro-negocio', [RegistroNegocioController::class, 'create'])
        ->name('registro.negocio');

    Route::post('/registro-negocio', [RegistroNegocioController::class, 'store'])
        ->name('registro.negocio.submit');
});

// Panel de comercios
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::post('/dashboard/productos', [DashboardController::class, 'storeProducto'])
        ->name('dashboard.producto.store');

    Route::put('/dashboard/productos/{idProducto}', [DashboardController::class, 'updateProducto'])
        ->name('dashboard.producto.update');

    Route::delete('/dashboard/productos/{idProducto}', [DashboardController::class, 'destroyProducto'])
        ->name('dashboard.producto.destroy');

    Route::post('/dashboard/pedidos/{pedido}/estado', [DashboardController::class, 'updatePedidoEstado'])
        ->name('dashboard.pedido.estado');

    Route::post('/dashboard/tipos-precio', [ConfiguracionNegocioController::class, 'crearTipoPrecio'])
        ->name('dashboard.tipos.precio.store');

    Route::post('/dashboard/tipos-entrega', [ConfiguracionNegocioController::class, 'crearTipoEntrega'])
        ->name('dashboard.tipos.entrega.store');

    Route::post('/dashboard/metodos-pago', [ConfiguracionNegocioController::class, 'crearMetodoPago'])
        ->name('dashboard.metodos.pago.store');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

// Registro de clientes
Route::get('/cliente/registro', [ClienteAuthController::class, 'mostrarRegistro'])
    ->name('cliente.registro');

Route::post('/cliente/registro', [ClienteAuthController::class, 'registrar'])
    ->name('cliente.registro.submit');

// Inicio de sesión de clientes
Route::get('/cliente/login', [ClienteAuthController::class, 'mostrarLogin'])
    ->name('cliente.login');

Route::post('/cliente/login', [ClienteAuthController::class, 'login'])
    ->name('cliente.login.submit');

// Funciones para clientes autenticados
Route::middleware('auth:clientes')->group(function () {

    Route::post('/cliente/logout', [ClienteAuthController::class, 'logout'])
        ->name('cliente.logout');

    // Consulta automática de nuevas notificaciones
    Route::get('/cliente/notificaciones/nuevas', [ClienteNotificacionController::class, 'nuevas'])
        ->name('cliente.notificaciones.nuevas');
});
