<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comercio extends Model
{
    protected $table = 'comercio';
    protected $primaryKey = 'id_comercio';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'email',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_comercio', 'id_comercio');
    }

    public function usuarios()
    {
        return $this->hasMany(User::class, 'id_comercio', 'id_comercio');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'id_comercio', 'id_comercio');
    }
}
