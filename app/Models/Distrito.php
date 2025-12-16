<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distrito extends Model
{
    protected $table = 'DISTRITO';
    protected $primaryKey = 'id_distrito';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_distrito',
        'nombre_distrito',
        'provincia',
        'region'
    ];

    // Relaciones
    public function apoderados()
    {
        return $this->hasMany(Apoderado::class, 'id_distrito');
    }

    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class, 'id_distrito');
    }
}
