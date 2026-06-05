<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membresia extends Model
{
    protected $table = 'Membresias';

    protected $primaryKey = 'MembresiaID';

    public $timestamps = false;

    protected $fillable = [
        'Tipo',
        'Precio',
        'CantidadClases'
    ];
    public function inscripciones()
{
    return $this->hasMany(Inscripcion::class, 'MembresiaID');
}
}
