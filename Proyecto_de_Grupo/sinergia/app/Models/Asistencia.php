<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table = 'Asistencias';

    protected $primaryKey = 'AsistenciaID';

    public $timestamps = false;

    protected $fillable = [
        'SocioID',
        'ClaseID',
        'FechaEntrada',
        'FechaSalida'
    ];

    public function socio()
    {
        return $this->belongsTo(Socio::class, 'SocioID');
    }

    public function clase()
    {
        return $this->belongsTo(Clase::class, 'ClaseID');
    }
}