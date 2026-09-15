<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedido';
    protected $primaryKey = 'id_pedido';
    public $timestamps = true;

    protected $fillable = [
        'id_usuario',
        'fecha',
        'id_tipo_entrega',
        'direccion_entrega',
        'fecha_programada',
        'hora_programada',
        'id_estado',
        'total',
        'id_comercio',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'fecha' => 'datetime',
    ];

    public function comercio()
    {
        return $this->belongsTo(Comercio::class, 'id_comercio', 'id_comercio');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'id_pedido', 'id_pedido');
    }

    public function estadoPedido()
    {
        return $this->belongsTo(EstadoPedido::class, 'id_estado', 'id_estado');
    }
}
