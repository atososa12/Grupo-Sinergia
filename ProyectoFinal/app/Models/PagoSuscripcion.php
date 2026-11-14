<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoSuscripcion extends Model
{
    protected $table = 'pago_suscripcion';
    protected $primaryKey = 'id_pago_suscripcion';
    public $timestamps = false;

    protected $fillable = [
        'id_suscripcion',
        'id_cuenta',
        'monto',
        'fecha_pago',
        'periodo_desde',
        'periodo_hasta',
        'comprobante',
        'estado',
        'fecha_revision',
        'observaciones',
        'fecha_creacion',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_pago' => 'datetime',
        'fecha_revision' => 'datetime',
        'periodo_desde' => 'date',
        'periodo_hasta' => 'date',
        'fecha_creacion' => 'datetime',
    ];

    public function suscripcion()
    {
        return $this->belongsTo(Suscripcion::class, 'id_suscripcion', 'id_suscripcion');
    }

    public function cuenta()
    {
        return $this->belongsTo(CuentaBancariaSinergia::class, 'id_cuenta', 'id_cuenta');
    }
}
