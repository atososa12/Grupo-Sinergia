<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoPrecio extends Model
{
    protected $table = 'producto_precio';
    protected $primaryKey = 'id_producto_precio';
    public $timestamps = false;

    protected $fillable = [
        'id_producto',
        'id_tipo_precio',
        'precio',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    public function tipoPrecio()
    {
        return $this->belongsTo(TipoPrecio::class, 'id_tipo_precio', 'id_tipo_precio');
    }
}
