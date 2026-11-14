<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfiguracionNegocioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegistroNegocioController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\ClienteAuthController;
use App\Http\Controllers\ClienteNotificacionController;
use App\Http\Controllers\ClientePedidoController;
use App\Http\Controllers\SuscripcionController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\VerificarAdmin;
use App\Http\Middleware\VerificarComercioActivo;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/comercios', [TiendaController::class, 'comercios'])->name('comercios.index');
Route::get('/comercio/{idComercio}', [TiendaController::class, 'show'])->name('comercio.show');
Route::post('/comercio/{idComercio}/pedido', [TiendaController::class, 'storePedido'])->name('comercio.pedido');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');

    Route::get('/register', [RegistroNegocioController::class, 'create'])->name('register');
    Route::post('/register', [RegistroNegocioController::class, 'store'])->name('register.submit');

    Route::get('/registro-negocio', [RegistroNegocioController::class, 'create'])->name('registro.negocio');
    Route::post('/registro-negocio', [RegistroNegocioController::class, 'store'])->name('registro.negocio.submit');
});

Route::middleware('auth')->group(function () {
    Route::get('/suscripcion/pago', [SuscripcionController::class, 'index'])
        ->name('suscripcion.pago');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(VerificarAdmin::class)->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::post('/pagos/{pago}/aprobar', [AdminController::class, 'aprobarPago'])->name('pagos.aprobar');
        Route::post('/pagos/{pago}/rechazar', [AdminController::class, 'rechazarPago'])->name('pagos.rechazar');
    });

    Route::middleware(VerificarComercioActivo::class)->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::post('/dashboard/notificaciones/{idNotificacion}/leida', [DashboardController::class, 'marcarNotificacionLeida'])
            ->name('dashboard.notificacion.leida');

        Route::post('/dashboard/productos', [DashboardController::class, 'storeProducto'])->name('dashboard.producto.store');
        Route::put('/dashboard/productos/{idProducto}', [DashboardController::class, 'updateProducto'])->name('dashboard.producto.update');
        Route::delete('/dashboard/productos/{idProducto}', [DashboardController::class, 'destroyProducto'])->name('dashboard.producto.destroy');

        Route::post('/dashboard/pedidos/{pedido}/estado', [DashboardController::class, 'updatePedidoEstado'])->name('dashboard.pedido.estado');

        Route::post('/dashboard/tipos-precio', [ConfiguracionNegocioController::class, 'crearTipoPrecio'])->name('dashboard.tipos.precio.store');
        Route::post('/dashboard/tipos-entrega', [ConfiguracionNegocioController::class, 'crearTipoEntrega'])->name('dashboard.tipos.entrega.store');

        Route::post('/dashboard/metodos-pago', [ConfiguracionNegocioController::class, 'crearMetodoPago'])->name('dashboard.metodos.pago.store');
    });
});

Route::get('/cliente/registro', [ClienteAuthController::class, 'mostrarRegistro'])->name('cliente.registro');
Route::post('/cliente/registro', [ClienteAuthController::class, 'registrar'])->name('cliente.registro.submit');

Route::get('/cliente/login', [ClienteAuthController::class, 'mostrarLogin'])->name('cliente.login');
Route::post('/cliente/login', [ClienteAuthController::class, 'login'])->name('cliente.login.submit');

Route::middleware('auth:clientes')->group(function () {
    Route::post('/cliente/logout', [ClienteAuthController::class, 'logout'])->name('cliente.logout');

    Route::get('/cliente/notificaciones/nuevas', [ClienteNotificacionController::class, 'nuevas'])
        ->name('cliente.notificaciones.nuevas');

    Route::get('/cliente/pedidos', [ClientePedidoController::class, 'index'])
        ->name('cliente.pedidos');

    Route::post('/cliente/pedidos/{idPedido}/cancelar', [ClientePedidoController::class, 'cancelar'])
        ->name('cliente.pedidos.cancelar');
});
