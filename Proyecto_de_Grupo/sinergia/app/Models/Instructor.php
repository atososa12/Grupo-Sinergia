<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $table = 'Instructores';

    protected $primaryKey = 'InstructorID';

    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Especialidad',
        'Email',
        'Telefono',
        'Activo'
    ];

    public function clases()
    {
        return $this->hasMany(Clase::class, 'InstructorID');
    }
    
    
}