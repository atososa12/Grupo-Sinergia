<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Socio extends Model
{
    protected $table = 'Socios';

    protected $primaryKey = 'SocioID';

    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Documento',
        'Email',
        'Telefono',
        'FechaNacimiento',
        'FechaAlta',
        'Activo'
    ];

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'SocioID');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'SocioID');
    }
}