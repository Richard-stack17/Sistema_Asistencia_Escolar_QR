<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'USUARIO';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'correo',
        'contrasenia_hash',
        'id_rol',
        'activo_',
        'ult_acceso',
        'fecha_registro'
    ];

    protected $hidden = [
        'contrasenia_hash'
    ];

    protected $casts = [
        'activo_' => 'boolean',
        'ult_acceso' => 'datetime',
        'fecha_registro' => 'datetime',
    ];

    // 🔑 Laravel Auth usa "password"
    public function getAuthPassword()
    {
        return $this->contrasenia_hash;
    }

    // Relaciones
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }
}
