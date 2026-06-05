<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'Pagos';

    protected $primaryKey = 'PagoID';

    public $timestamps = false;

    protected $fillable = [
        'InscripcionID',
        'Monto',
        'FechaPago',
        'MedioPago'
    ];

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'InscripcionID');
    }
}