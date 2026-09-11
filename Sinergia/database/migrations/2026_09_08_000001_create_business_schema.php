<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comercio', function (Blueprint $table) {
            $table->id('id_comercio');
            $table->string('nombre', 150);
            $table->string('direccion', 255)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('email', 150)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('usuario', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('email', 150)->unique();
            $table->string('contrasena', 255);
            $table->string('telefono', 30)->nullable();
            $table->string('rol', 50)->default('dueño');
            $table->unsignedBigInteger('id_comercio')->nullable();
            $table->timestamps();

            $table->foreign('id_comercio')->references('id_comercio')->on('comercio')->onDelete('set null');
        });

        Schema::create('tipo_precio', function (Blueprint $table) {
            $table->id('id_tipo_precio');
            $table->string('nombre', 100);
            $table->string('descripcion', 255)->nullable();
            $table->boolean('activo')->default(true);
        });

        Schema::create('tipo_entrega', function (Blueprint $table) {
            $table->id('id_tipo_entrega');
            $table->string('nombre', 100);
            $table->boolean('activo')->default(true);
        });

        Schema::create('estado_pedido', function (Blueprint $table) {
            $table->id('id_estado');
            $table->string('nombre', 100);
            $table->boolean('activo')->default(true);
        });

        Schema::create('metodo_pago', function (Blueprint $table) {
            $table->id('id_metodo_pago');
            $table->string('nombre', 100);
            $table->boolean('activo')->default(true);
        });

        Schema::create('estado_pago', function (Blueprint $table) {
            $table->id('id_estado_pago');
            $table->string('nombre', 100);
            $table->boolean('activo')->default(true);
        });

        Schema::create('producto', function (Blueprint $table) {
            $table->id('id_producto');
            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();
            $table->boolean('disponible')->default(true);
            $table->string('imagen', 255)->nullable();
            $table->integer('stock')->default(0);
            $table->unsignedBigInteger('id_comercio')->nullable();
            $table->timestamps();

            $table->foreign('id_comercio')->references('id_comercio')->on('comercio')->onDelete('cascade');
        });

        Schema::create('producto_precio', function (Blueprint $table) {
            $table->id('id_producto_precio');
            $table->unsignedBigInteger('id_producto');
            $table->unsignedBigInteger('id_tipo_precio');
            $table->decimal('precio', 10, 2);

            $table->foreign('id_producto')->references('id_producto')->on('producto')->onDelete('cascade');
            $table->foreign('id_tipo_precio')->references('id_tipo_precio')->on('tipo_precio')->onDelete('cascade');
        });

        Schema::create('pedido', function (Blueprint $table) {
            $table->id('id_pedido');
            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->dateTime('fecha')->nullable();
            $table->unsignedBigInteger('id_tipo_entrega')->nullable();
            $table->string('direccion_entrega', 255)->nullable();
            $table->date('fecha_programada')->nullable();
            $table->time('hora_programada')->nullable();
            $table->unsignedBigInteger('id_estado')->nullable();
            $table->decimal('total', 10, 2)->default(0);
            $table->unsignedBigInteger('id_comercio')->nullable();
            $table->timestamps();

            $table->foreign('id_usuario')->references('id_usuario')->on('usuario')->onDelete('set null');
            $table->foreign('id_tipo_entrega')->references('id_tipo_entrega')->on('tipo_entrega')->onDelete('set null');
            $table->foreign('id_estado')->references('id_estado')->on('estado_pedido')->onDelete('set null');
            $table->foreign('id_comercio')->references('id_comercio')->on('comercio')->onDelete('set null');
        });

        Schema::create('detalle_pedido', function (Blueprint $table) {
            $table->id('id_detalle');
            $table->unsignedBigInteger('id_pedido');
            $table->unsignedBigInteger('id_producto')->nullable();
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);

            $table->foreign('id_pedido')->references('id_pedido')->on('pedido')->onDelete('cascade');
            $table->foreign('id_producto')->references('id_producto')->on('producto')->onDelete('set null');
        });

        Schema::create('pago', function (Blueprint $table) {
            $table->id('id_pago');
            $table->unsignedBigInteger('id_pedido');
            $table->unsignedBigInteger('id_metodo_pago')->nullable();
            $table->decimal('monto', 10, 2);
            $table->dateTime('fecha_pago')->nullable();
            $table->unsignedBigInteger('id_estado_pago')->nullable();
            $table->timestamps();

            $table->foreign('id_pedido')->references('id_pedido')->on('pedido')->onDelete('cascade');
            $table->foreign('id_metodo_pago')->references('id_metodo_pago')->on('metodo_pago')->onDelete('set null');
            $table->foreign('id_estado_pago')->references('id_estado_pago')->on('estado_pago')->onDelete('set null');
        });

        Schema::create('horarios_atencion', function (Blueprint $table) {
            $table->id('id_horario');
            $table->unsignedBigInteger('id_comercio');
            $table->string('dia_semana', 20);
            $table->time('hora_apertura')->nullable();
            $table->time('hora_cierre')->nullable();
            $table->boolean('abierto')->default(true);
            $table->timestamps();

            $table->foreign('id_comercio')->references('id_comercio')->on('comercio')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios_atencion');
        Schema::dropIfExists('pago');
        Schema::dropIfExists('detalle_pedido');
        Schema::dropIfExists('pedido');
        Schema::dropIfExists('producto_precio');
        Schema::dropIfExists('producto');
        Schema::dropIfExists('estado_pago');
        Schema::dropIfExists('metodo_pago');
        Schema::dropIfExists('estado_pedido');
        Schema::dropIfExists('tipo_entrega');
        Schema::dropIfExists('tipo_precio');
        Schema::dropIfExists('usuario');
        Schema::dropIfExists('comercio');
    }
};
