@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-8">

    <h2 class="text-2xl font-bold text-gray-800 mb-6">Detalles del Usuario</h2>

    <div class="bg-white shadow rounded-lg p-6 space-y-4">
        <div><strong>Nombre:</strong> {{ $usuario->nombre }}</div>
        <div><strong>Correo:</strong> {{ $usuario->correo }}</div>
        <div><strong>Rol:</strong> {{ $usuario->rol->nombre_rol ?? '-' }}</div>
        <div>
            <strong>Activo:</strong>
            @if($usuario->activo_)
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Sí</span>
            @else
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">No</span>
            @endif
        </div>
        <div>
            <strong>Último acceso:</strong> {{ $usuario->ult_acceso ? $usuario->ult_acceso->format('d/m/Y H:i') : 'No hay último acceso' }}
        </div>
        <div>
            <strong>Fecha de registro:</strong> {{ $usuario->fecha_registro ? $usuario->fecha_registro->format('d/m/Y H:i') : '-' }}
        </div>
    </div>

    <div class="mt-6 flex gap-3">
        <a href="{{ route('usuarios.edit', $usuario->id_usuario) }}"
           class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md">Editar</a>
        <a href="{{ route('usuarios.index') }}"
           class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md">Volver al listado</a>
    </div>
</div>
@endsection
