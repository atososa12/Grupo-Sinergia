<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificacionCliente extends Model
{
    protected $table = 'notificacion_cliente';

    protected $primaryKey = 'id_notificacion';

    public $timestamps = false;

    protected $fillable = [
        'id_cliente',
        'id_pedido',
        'id_estado',
        'mensaje',
        'leida',
        'fecha',
    ];
}