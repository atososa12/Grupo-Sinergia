<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificacionComercio extends Model
{
    protected $table = 'notificacion_comercio';

    protected $primaryKey = 'id_notificacion';

    public $timestamps = false;

    protected $fillable = [
        'id_comercio',
        'id_pago_suscripcion',
        'titulo',
        'mensaje',
        'tipo',
        'leida',
        'fecha_creacion',
    ];

    protected $casts = [
        'leida' => 'boolean',
        'fecha_creacion' => 'datetime',
    ];

    public function comercio()
    {
        return $this->belongsTo(
            Comercio::class,
            'id_comercio',
            'id_comercio'
        );
    }

    public function pagoSuscripcion()
    {
        return $this->belongsTo(
            PagoSuscripcion::class,
            'id_pago_suscripcion',
            'id_pago_suscripcion'
        );
    }
}
