<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suscripcion extends Model
{
    protected $table = 'suscripcion';
    protected $primaryKey = 'id_suscripcion';
    public $timestamps = false;

    protected $fillable = [
        'id_comercio',
        'fecha_inicio',
        'fecha_vencimiento',
        'monto',
        'periodicidad',
        'estado',
        'meses_promocion',
        'monto_renovacion',
        'fecha_creacion',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_vencimiento' => 'date',
        'fecha_creacion' => 'datetime',
        'monto' => 'decimal:2',
        'monto_renovacion' => 'decimal:2',
    ];

    public function comercio()
    {
        return $this->belongsTo(Comercio::class, 'id_comercio', 'id_comercio');
    }

    public function pagos()
    {
        return $this->hasMany(PagoSuscripcion::class, 'id_suscripcion', 'id_suscripcion');
    }
}
