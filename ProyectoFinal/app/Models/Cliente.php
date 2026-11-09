<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Cliente extends Authenticatable
{
    protected $connection = 'clientes';

    protected $table = 'Clientes';

    protected $primaryKey = 'id_cliente';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellido',
        'telefono',
        'email',
        'contrasena',
    ];

    protected $hidden = [
        'contrasena',
    ];

    public function getAuthPassword()
    {
        return $this->contrasena;
    }
}