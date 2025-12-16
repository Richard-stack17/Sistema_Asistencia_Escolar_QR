<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstudianteApoderado extends Model
{
    protected $table = 'ESTUDIANTE_APODERADO';
    protected $primaryKey = 'id_e_p';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'Id_estudiante',
        'id_apoderado',
        'id_e_p',
        'parentesco',
        'fecha_registro',
        'es_principal_'
    ];

    protected $casts = [
        'es_principal_' => 'boolean',
        'fecha_registro' => 'datetime'
    ];
}
