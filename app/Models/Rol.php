<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'ROL';
    protected $primaryKey = 'id_rol';
    public $timestamps = false;

    protected $fillable = [
        'id_rol',
        'nombre_rol',
        'descripcion'
    ];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_rol');
    }
}
