<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogSesion extends Model
{
    protected $table = 'LOG_SESION';
    protected $primaryKey = 'id_sesion';
    public $timestamps = false;

    protected $fillable = [
        'id_sesion',
        'fecha_ingreso',
        'fecha_fin',
        'hora_ingreso',
        'hora_salida',
        'estado',
        'direccion_ip',
        'id_usuario',
        'token',
        'dispositivo'
    ];

    protected $casts = [
        'fecha_ingreso' => 'datetime',
        'fecha_fin' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
