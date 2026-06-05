<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    protected $table = 'Clases';

    protected $primaryKey = 'ClaseID';

    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Tipo',
        'InstructorID',
        'Dias',
        'Horario',
        'CupoMaximo'
    ];

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'InstructorID');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'ClaseID');
    }
}