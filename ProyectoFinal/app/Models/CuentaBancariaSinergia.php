<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuentaBancariaSinergia extends Model
{
    protected $table = 'cuenta_bancaria_sinergia';
    protected $primaryKey = 'id_cuenta';
    public $timestamps = false;

    protected $fillable = [
        'banco',
        'titular',
        'numero_cuenta',
        'alias',
        'moneda',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function pagos()
    {
        return $this->hasMany(PagoSuscripcion::class, 'id_cuenta', 'id_cuenta');
    }
}
