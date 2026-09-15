<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioAtencion extends Model
{
    protected $table = 'horarios_atencion';

    protected $primaryKey = 'id_horario';

    public $timestamps = false;

    protected $fillable = [
        'id_comercio',
        'dia_semana',
        'hora_apertura',
        'hora_cierre',
        'abierto',
    ];

    public function comercio()
    {
        return $this->belongsTo(Comercio::class, 'id_comercio', 'id_comercio');
    }
}