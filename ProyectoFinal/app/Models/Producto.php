<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';
    protected $primaryKey = 'id_producto';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'disponible',
        'imagen',
        'stock',
        'id_comercio',
    ];

    protected $casts = [
        'disponible' => 'boolean',
        'stock' => 'integer',
    ];

    public function comercio()
    {
        return $this->belongsTo(Comercio::class, 'id_comercio', 'id_comercio');
    }

    public function precios()
    {
        return $this->hasMany(ProductoPrecio::class, 'id_producto', 'id_producto');
    }
}
