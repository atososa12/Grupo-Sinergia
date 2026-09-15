<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoPrecio extends Model
{
    protected $table = 'tipo_precio';
    protected $primaryKey = 'id_tipo_precio';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
    ];
}
