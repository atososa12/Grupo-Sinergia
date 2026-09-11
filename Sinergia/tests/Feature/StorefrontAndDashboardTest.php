<?php

namespace Tests\Feature;

use App\Models\Comercio;
use App\Models\Producto;
use App\Models\ProductoPrecio;
use App\Models\TipoPrecio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontAndDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_storefront_loads_products_for_a_comercio(): void
    {
        $comercio = Comercio::create([
            'nombre' => 'Café del Sol',
            'direccion' => 'Av. Siempre Viva 123',
            'telefono' => '123456',
            'email' => 'cafe@sol.com',
            'activo' => true,
        ]);

        $producto = Producto::create([
            'nombre' => 'Café Latte',
            'descripcion' => 'Café espresso con leche',
            'disponible' => true,
            'stock' => 10,
            'id_comercio' => $comercio->id_comercio,
        ]);

        $tipoPrecio = TipoPrecio::create([
            'nombre' => 'Normal',
            'descripcion' => 'Precio regular',
            'activo' => true,
        ]);

        ProductoPrecio::create([
            'id_producto' => $producto->id_producto,
            'id_tipo_precio' => $tipoPrecio->id_tipo_precio,
            'precio' => 1800.00,
        ]);

        $response = $this->get('/comercio/' . $comercio->id_comercio);

        $response->assertOk();
        $response->assertSee('Café Latte');
        $response->assertSee('1800');
    }

    public function test_dashboard_requires_authentication(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_business_owner_can_access_dashboard(): void
    {
        $comercio = Comercio::create([
            'nombre' => 'Panadería El Ajo',
            'direccion' => 'Calle Falsa 321',
            'telefono' => '987654',
            'email' => 'ajo@panaderia.com',
            'activo' => true,
        ]);

        $user = User::create([
            'nombre' => 'Ana',
            'apellido' => 'Perez',
            'email' => 'ana@panaderia.com',
            'contrasena' => bcrypt('secret123'),
            'telefono' => '555',
            'rol' => 'dueño',
            'id_comercio' => $comercio->id_comercio,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Panadería El Ajo');
    }

    public function test_business_owner_can_register_a_new_commerce_and_login(): void
    {
        $response = $this->post('/registro-negocio', [
            'nombre_comercio' => 'Merca Local',
            'direccion' => 'Belgrano 120',
            'telefono' => '1122334455',
            'email_comercio' => 'mercalocal@test.com',
            'nombre' => 'Lucía',
            'apellido' => 'Gómez',
            'email' => 'lucia@mercalocal.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('comercio', ['nombre' => 'Merca Local']);
        $this->assertDatabaseHas('usuario', ['email' => 'lucia@mercalocal.com']);
    }

    public function test_product_can_be_created_when_no_price_type_exists_yet(): void
    {
        $comercio = Comercio::create([
            'nombre' => 'Pizzería Roma',
            'direccion' => 'Calle 9',
            'telefono' => '555000',
            'email' => 'roma@test.com',
            'activo' => true,
        ]);

        $user = User::create([
            'nombre' => 'Mario',
            'apellido' => 'Rossi',
            'email' => 'mario@roma.com',
            'contrasena' => bcrypt('secret123'),
            'telefono' => '111',
            'rol' => 'dueño',
            'id_comercio' => $comercio->id_comercio,
        ]);

        $response = $this->actingAs($user)->post('/dashboard/productos', [
            'nombre' => 'Pizza Margarita',
            'descripcion' => 'Pizza con salsa',
            'stock' => 99,
            'precio' => 500,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('producto', ['nombre' => 'Pizza Margarita', 'id_comercio' => $comercio->id_comercio]);
        $this->assertDatabaseHas('tipo_precio', ['nombre' => 'General']);
    }

    public function test_business_owner_can_create_extra_delivery_price_and_payment_options(): void
    {
        $comercio = Comercio::create([
            'nombre' => 'Cafetería Nova',
            'direccion' => 'San Martín 210',
            'telefono' => '111222',
            'email' => 'nova@test.com',
            'activo' => true,
        ]);

        $user = User::create([
            'nombre' => 'Laura',
            'apellido' => 'Salas',
            'email' => 'laura@nova.com',
            'contrasena' => bcrypt('secret123'),
            'telefono' => '333',
            'rol' => 'dueño',
            'id_comercio' => $comercio->id_comercio,
        ]);

        $this->actingAs($user)
            ->post('/dashboard/tipos-precio', ['nombre' => 'Mayorista', 'descripcion' => 'Precio por mayor', 'activo' => true]);

        $this->actingAs($user)
            ->post('/dashboard/tipos-entrega', ['nombre' => 'Express', 'activo' => true]);

        $this->actingAs($user)
            ->post('/dashboard/metodos-pago', ['nombre' => 'Tarjeta', 'activo' => true]);

        $this->assertDatabaseHas('tipo_precio', ['nombre' => 'Mayorista']);
        $this->assertDatabaseHas('tipo_entrega', ['nombre' => 'Express']);
        $this->assertDatabaseHas('metodo_pago', ['nombre' => 'Tarjeta']);
    }
}
