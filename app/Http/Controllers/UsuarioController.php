<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    // Listado de usuarios
    public function index()
    {
        $usuarios = Usuario::with('rol')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    // Ver detalles de un usuario
    public function show($id)
    {
        $usuario = Usuario::with('rol')->findOrFail($id);
        return view('usuarios.show', compact('usuario'));
    }

    // Formulario de creación
    public function create()
    {
        $roles = Rol::all();
        return view('usuarios.create', compact('roles'));
    }

    // Guardar nuevo usuario
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|unique:USUARIO,correo',
            'password' => 'required|min:6',
            'id_rol' => 'required|exists:ROL,id_rol',
        ]);

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'contrasenia_hash' => Hash::make($request->password),
            'id_rol' => $request->id_rol,
            'activo_' => 1,
            'fecha_registro' => now(),
        ]);

        Auditoria::create([
            'id_usuario' => session('usuario_id'),
            'tabla_afectada' => 'usuario',
            'registro_id' => $usuario->id_usuario,
            'accion' => 'CREAR',
            'cambios' => [
                'nombre' => $usuario->nombre,
                'correo' => $usuario->correo,
                'id_rol' => $usuario->id_rol,
                'activo_' => $usuario->activo_,
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente');
    }

    // Formulario para editar usuario
    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        $roles = Rol::all();
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    // Actualizar usuario
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|unique:USUARIO,correo,' . $id . ',id_usuario',
            'id_rol' => 'required|exists:ROL,id_rol',
            'activo_' => 'required|boolean',
        ]);

        $usuario->update([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'id_rol' => $request->id_rol,
            'activo_' => $request->activo_,
        ]);

        Auditoria::create([
            'id_usuario' => session('usuario_id'),
            'tabla_afectada' => 'usuario',
            'registro_id' => $usuario->id_usuario,
            'accion' => 'ACTUALIZAR',
            'cambios' => [
                'nombre' => $usuario->nombre,
                'correo' => $usuario->correo,
                'id_rol' => $usuario->id_rol,
                'activo_' => $usuario->activo_,
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente');
    }

    // Eliminar usuario
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete(); // si quieres eliminación lógica, reemplaza por: $usuario->update(['activo_' => 0]);

        Auditoria::create([
            'id_usuario' => session('usuario_id'),
            'tabla_afectada' => 'usuario',
            'registro_id' => $usuario->id_usuario,
            'accion' => 'ELIMINAR',
            'cambios' => [
                'nombre' => $usuario->nombre,
                'correo' => $usuario->correo,
                'id_rol' => $usuario->id_rol,
                'activo_' => $usuario->activo_,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente');
    }
}
