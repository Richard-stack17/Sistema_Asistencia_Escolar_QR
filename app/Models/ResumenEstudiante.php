<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumenEstudiante extends Model
{
    protected $table = 'RESUMEN_ESTUDIANTE';
    protected $primaryKey = 'id_resumen';
    public $timestamps = false;

    protected $fillable = [
        'id_resumen',
        'periodo',
        'anio',
        'mes',
        'tot_asistencias',
        'tot_tardanzas',
        'tot_faltas',
        'tot_justificaciones',
        'porc_asistencias',
        'porc_tardanza',
        'porc_faltas',
        'formato',
        'Id_estudiante',
        'id_usuario',
        'fecha_generado',
        'direccion_ip',
        'dispositivo_de_generacion',
        'hora_generado'
    ];

    protected $casts = [
        'fecha_generado' => 'datetime',
        'hora_generado' => 'datetime:H:i'
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'Id_estudiante');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
