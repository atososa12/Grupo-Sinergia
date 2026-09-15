<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoPago extends Model
{
    protected $table = 'estados_pago';

    protected $primaryKey = 'id_estado_pago';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'activo',
    ];

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_estado_pago', 'id_estado_pago');
    }
}