<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table = 'Inscripciones';

    protected $primaryKey = 'InscripcionID';

    public $timestamps = false;

    protected $fillable = [
        'SocioID',
        'MembresiaID',
        'FechaInicio',
        'FechaVencimiento',
        'Estado'
    ];

    public function socio()
    {
        return $this->belongsTo(Socio::class, 'SocioID');
    }

    public function membresia()
    {
        return $this->belongsTo(Membresia::class, 'MembresiaID');
    }
    public function pagos()
{
    return $this->hasMany(Pago::class, 'InscripcionID');
}
}