@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">

    {{-- ENCABEZADO --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
                Control de Asistencias
            </h2>
            <p class="text-gray-500 mt-1">
                Monitoreo diario y gestión de justificaciones.
            </p>
        </div>
    </div>

    {{-- BARRA DE HERRAMIENTAS / FILTROS --}}
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 sticky top-4 z-20">
        <form method="GET" action="{{ route('asistencias.index') }}" class="flex flex-col md:flex-row gap-4 items-center" id="filterForm">

            {{-- Búsqueda por Nombre --}}
            <div class="relative w-full md:w-1/4">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar estudiante..."
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    onchange="document.getElementById('filterForm').submit()">
            </div>
            {{-- Filtro por Fecha --}}
            <div class="w-full md:w-1/6">
                <input type="date"
                    name="fecha"
                    value="{{ request('fecha') }}"
                    onchange="document.getElementById('filterForm').submit()"
                    class="block w-full py-2 px-3 border border-gray-300 rounded-lg
                  focus:outline-none focus:ring-blue-500 focus:border-blue-500
                  text-sm cursor-pointer">
            </div>

            {{-- Filtro de Estado --}}
            <div class="w-full md:w-1/6">
                <select name="estado" onchange="document.getElementById('filterForm').submit()"
                    class="block w-full py-2 pl-3 pr-10 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg cursor-pointer">
                    <option value="">Estados</option>
                    <option value="PUNTUAL" {{ request('estado')=='PUNTUAL' ? 'selected' : '' }}>✅ Puntual</option>
                    <option value="TARDANZA" {{ request('estado')=='TARDANZA' ? 'selected' : '' }}>⚠️ Tardanza</option>
                    <option value="FALTA" {{ request('estado')=='FALTA' ? 'selected' : '' }}>❌ Inasistencia</option>
                    <option value="JUSTIFICADO" {{ request('estado')=='JUSTIFICADO' ? 'selected' : '' }}>📄 Justificado</option>
                </select>
            </div>

            {{-- Filtro de Grado --}}
            <div class="w-full md:w-1/6">
                <select name="grado" onchange="document.getElementById('filterForm').submit()"
                    class="block w-full py-2 pl-3 pr-10 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg cursor-pointer">
                    <option value="">Grado</option>
                    @foreach($grados as $grado)
                    <option value="{{ $grado }}" {{ request('grado') == $grado ? 'selected' : '' }}>{{ $grado }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filtro de Sección --}}
            <div class="w-full md:w-1/6">
                <select name="seccion" onchange="document.getElementById('filterForm').submit()"
                    class="block w-full py-2 pl-3 pr-10 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg cursor-pointer">
                    <option value="">Sección</option>
                    @foreach($secciones as $seccion)
                    <option value="{{ $seccion }}" {{ request('seccion') == $seccion ? 'selected' : '' }}>{{ $seccion }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Solo revisiones pendientes --}}
            <div class="w-full md:w-1/6 flex items-center">
                <label class="inline-flex items-center space-x-2 text-sm text-gray-700">
                    <input type="checkbox" name="pendiente" value="1" {{ request('pendiente') ? 'checked' : '' }}
                        onchange="document.getElementById('filterForm').submit()">
                    <span>Revisiones Pendientes</span>
                </label>
            </div>

            {{-- Restablecer --}}
            <div class="w-full md:w-auto ml-auto">
                <a href="{{ route('asistencias.index') }}" class="text-sm text-orange-500 hover:text-red-600 font-medium underline">
                    Restablecer
                </a>
            </div>


        </form>
    </div>


    {{-- TABLA --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha / Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revision</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Justificación</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($asistencias as $a)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">

                        {{-- ESTUDIANTE --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-sm">
                                        {{ substr($a->estudiante->nombres, 0, 1) }}{{ substr($a->estudiante->apellidos, 0, 1) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $a->estudiante->nombres }} {{ $a->estudiante->apellidos }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        DNI: {{ $a->estudiante->dni ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        GRADO: {{ $a->estudiante->grado ?? 'N/A' }}, SECCION: {{ $a->estudiante->seccion ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- FECHA / HORA --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-medium">
                                {{ \Carbon\Carbon::parse($a->fecha)->format('d M, Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($a->hora_escaneo)->format('h:i A') }}
                            </div>
                        </td>

                        {{-- ESTADO --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                            $estadoClasses = [
                            'PUNTUAL' => 'bg-green-100 text-green-800 border-green-200',
                            'TARDANZA' => 'bg-amber-100 text-amber-800 border-amber-200',
                            'FALTA' => 'bg-red-100 text-red-800 border-red-200',
                            'JUSTIFICADO' => 'bg-purple-100 text-purple-800 border-purple-200',
                            ];
                            $clase = $estadoClasses[$a->estado_Asist] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $clase }}">
                                {{ ucfirst(strtolower($a->estado_Asist)) }}
                            </span>
                        </td>

                        {{-- JUSTIFICACIÓN --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($a->estado_Asist === 'PUNTUAL')
                            {{-- Nada para puntual --}}
                            <span class="text-gray-400 text-xs italic"></span>
                            @elseif($a->justificacion)
                            @if($a->justificacion->estado === 'Pendiente')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                <svg class="mr-1.5 h-2 w-2 text-yellow-400" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Pendiente
                            </span>
                            @elseif($a->justificacion->estado === 'Aprobado')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                <svg class="mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Aprobada
                            </span>
                            @elseif($a->justificacion->estado === 'Rechazado')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                <svg class="mr-1.5 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Rechazada
                            </span>
                            @endif

                            {{-- Ver Observación --}}
                            @if($a->justificacion->revisiones->isNotEmpty())
                            @php $ultimaRevision = $a->justificacion->revisiones->last(); @endphp
                            <div class="mt-1">
                                <button type="button"
                                    onclick="document.getElementById('obs-{{$a->ID_asistencia}}').classList.toggle('hidden')"
                                    class="text-blue-600 hover:text-blue-800 text-xs font-semibold flex items-center gap-1 transition duration-300 ease-in-out cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3C7 3 4 8 4 8s3 5 8 5 8-5 8-5-3-5-8-5zm0 6c-1.5 0-3-1.5-3-3s1.5-3 3-3 3 1.5 3 3-1.5 3-3 3z" />
                                    </svg>
                                    <span>Ver Observación</span>
                                </button>

                                <div id="obs-{{$a->ID_asistencia}}" class="hidden mt-2 p-2 border-l-4 border-orange-500 bg-gray-50 rounded text-xs text-gray-700 max-w-[100px] break-words whitespace-normal">
                                    <strong>Detalle:</strong>
                                    <p>{{ $ultimaRevision->observacion ?? 'Sin observación' }}</p>
                                </div>
                            </div>
                            @endif
                            @else
                            {{-- Solo mostrar "Sin justificación" si es TARDANZA o FALTA --}}
                            @if(in_array($a->estado_Asist, ['TARDANZA', 'FALTA']))
                            <span class="text-gray-400 text-xs italic">Sin justificación</span>
                            @endif
                            @endif
                        </td>

                        {{-- ACCIONES --}}
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                            @if($a->justificacion)
                            @if($a->justificacion->estado === 'Aprobado' || $a->justificacion->estado === 'Rechazado')
                            <a href="{{ route('justificaciones.show', $a->justificacion->id_justificacion) }}"
                                class="text-gray-600 hover:text-gray-900 font-semibold hover:underline">
                                Ver
                            </a>
                            @else
                            <a href="{{ route('justificaciones.edit', $a->justificacion->id_justificacion) }}"
                                class="text-blue-600 hover:text-blue-900 font-semibold hover:underline">
                                Ver / Editar
                            </a>
                            @endif
                            @else
                            @if(in_array($a->estado_Asist, ['TARDANZA', 'FALTA']))
                            <a href="{{ route('justificaciones.create', $a->ID_asistencia) }}"
                                class="text-orange-600 hover:text-orange-900 font-semibold hover:underline">
                                Justificar
                            </a>
                            @endif
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-lg font-medium">No se encontraron registros</p>
                                <p class="text-sm">Intenta ajustar los filtros de búsqueda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection