@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- ENCABEZADO --}}
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">
            Revisión de Justificaciones
        </h2>
        <p class="text-sm text-gray-500">
            Evaluación académica de inasistencias y tardanzas registradas
        </p>
    </div>

    {{-- FILTRO --}}
    <div class="mb-6 bg-white p-5 rounded-xl shadow-sm border">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

            {{-- ESTADO --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">
                    Estado
                </label>
                <select name="estado"
                    class="w-full border-gray-300 rounded-lg px-3 py-2
                focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    <option value="">Todos</option>
                    <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="Aprobado" {{ request('estado') == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                    <option value="Rechazado" {{ request('estado') == 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
                </select>
            </div>

            {{-- FECHA --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">
                    Fecha de asistencia
                </label>
                <input type="date"
                    name="fecha"
                    value="{{ request('fecha') }}"
                    class="w-full border-gray-300 rounded-lg px-3 py-2
                focus:ring-2 focus:ring-orange-500 focus:outline-none">
            </div>

            {{-- BOTÓN FILTRAR --}}
            <div>
                <button type="submit"
                    class="w-full bg-orange-600 text-white px-4 py-2 rounded-lg
                hover:bg-orange-700 font-semibold">
                    Filtrar
                </button>
            </div>

            {{-- BOTÓN LIMPIAR --}}
            @if(request()->filled('estado') || request()->filled('fecha'))
            <div>
                <a href="{{ route('director.justificaciones') }}"
                    class="w-full inline-block text-center bg-gray-500 text-white px-4 py-2
                rounded-lg hover:bg-gray-600 font-semibold">
                    Limpiar
                </a>
            </div>
            @endif

        </form>
    </div>


    {{-- LISTADO --}}
    <div class="space-y-6">

        @forelse($justificaciones as $j)
        <div class="bg-white rounded-xl shadow border border-gray-200">

            {{-- HEADER TARJETA --}}
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <div>
                    <p class="font-semibold text-gray-800">
                        {{ $j->asistencia->estudiante->nombres }}
                    </p>
                    <p class="text-xs text-gray-500">
                        Fecha de envío:
                        {{ \Carbon\Carbon::parse($j->fecha_envio)->format('d/m/Y H:i') }}
                    </p>
                </div>

                {{-- ESTADO --}}
                @php
                $estadoClasses = [
                'Pendiente' => 'bg-yellow-100 text-yellow-700',
                'Aprobado' => 'bg-green-100 text-green-700',
                'Rechazado' => 'bg-red-100 text-red-700'
                ];
                @endphp

                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold {{ $estadoClasses[$j->estado] ?? '' }}">
                    {{ $j->estado }}
                </span>
            </div>

            {{-- CONTENIDO --}}
            <div class="grid md:grid-cols-2 gap-6 px-6 py-5 text-sm">

                {{-- DATOS DEL ESTUDIANTE --}}
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">Datos del estudiante</h4>
                    <ul class="text-gray-600 space-y-1">
                        <li><strong>Fecha asistencia:</strong> {{ \Carbon\Carbon::parse($j->asistencia->fecha)->format('d/m/Y') }}</li>
                        <li><strong>Hora escaneo:</strong> {{ $j->asistencia->hora_escaneo }}</li>
                        <li><strong>Estado registrado:</strong> {{ $j->asistencia->estado_Asist }}</li>
                    </ul>
                </div>

                {{-- MOTIVO --}}
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">Motivo de la justificación</h4>
                    <p class="text-gray-600 leading-relaxed">{{ $j->motivo }}</p>

                    @if($j->rutaArchivo)
                    <a href="{{ asset('storage/'.$j->rutaArchivo) }}" target="_blank"
                        class="inline-flex items-center gap-2 mt-3 text-orange-600 hover:underline text-sm">
                        Ver documento adjunto
                    </a>
                    @endif

                    @if($j->revision && !empty($j->revision->observacion))
                    <div class="mt-4 p-3 bg-gray-100 rounded-md text-gray-700 text-sm border-l-4 border-gray-300">
                        <strong>Comentario del Director:</strong>
                        <p class="mt-1">{{ $j->revision->observacion }}</p>
                    </div>
                    @else
                    <div class="mt-4 p-3 bg-red-100 rounded-md text-gray-700 text-sm border-l-4 border-red-400">
                        <p class="mt-1 text-red-700 italic">Sin comentario u observación</p>
                    </div>
                    @endif
                </div>

            </div>

            {{-- ACCIONES --}}
            @if($j->estado === 'Pendiente')
            <div class="bg-gray-50 px-6 py-4 border-t">
                <form method="POST" action="{{ route('director.justificaciones.resolver', $j->id_justificacion) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                            Observación del Director (obligatoria)
                        </label>
                        <textarea name="observacion" required rows="2"
                            class="w-full border rounded-md px-3 py-2 text-sm
                            focus:ring-2 focus:ring-orange-500 focus:outline-none"></textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button name="accion" value="rechazar"
                            class="px-4 py-2 rounded-md text-sm bg-red-600 hover:bg-red-700 text-white font-semibold">
                            Rechazar
                        </button>

                        <button name="accion" value="aprobar"
                            class="px-4 py-2 rounded-md text-sm bg-green-600 hover:bg-green-700 text-white font-semibold">
                            Aprobar
                        </button>
                    </div>
                </form>
            </div>
            @endif

        </div>
        @empty
        <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
            No hay justificaciones según el filtro seleccionado.
        </div>
        @endforelse

    </div>

</div>
@endsection