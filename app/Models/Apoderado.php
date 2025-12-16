<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apoderado extends Model
{
    protected $table = 'APODERADO';
    protected $primaryKey = 'id_apoderado';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_apoderado',
        'nombres',
        'Apellidos',
        'telefono',
        'correo',
        'direccion',
        'distrito',
        'id_distrito',
        'dni'
    ];

    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'id_distrito');
    }

    public function estudiantes()
    {
        return $this->belongsToMany(
            Estudiante::class,
            'estudiante_apoderado',
            'id_apoderado',
            'Id_estudiante'
        )->withPivot(['parentesco', 'fecha_registro', 'es_principal_', 'id_e_p']);
    }
}
