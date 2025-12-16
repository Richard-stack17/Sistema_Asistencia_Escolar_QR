<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Asistencia;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AsistenciaController extends Controller
{
    public function index(Request $request)
    {
        // Listado de grados y secciones fijas
        $grados = ['Primero', 'Segundo', 'Tercero', 'Cuarto', 'Quinto'];
        $secciones = ['A', 'B', 'C', 'D', 'E', 'F'];

        $query = Asistencia::with(['estudiante', 'justificacion.revisiones'])
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_escaneo', 'desc');

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado_Asist', $request->estado);
        }

        // Filtro por búsqueda de nombre/apellidos/código QR
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('estudiante', function ($q) use ($search) {
                $q->where('nombres', 'LIKE', "%$search%")
                    ->orWhere('apellidos', 'LIKE', "%$search%")
                    ->orWhere('codigo_qr', 'LIKE', "%$search%");
            });
        }

        // Filtro por grado
        if ($request->filled('grado')) {
            $grado = $request->grado;
            $query->whereHas('estudiante', function ($q) use ($grado) {
                $q->where('grado', $grado);
            });
        }

        // Filtro por sección
        if ($request->filled('seccion')) {
            $seccion = $request->seccion;
            $query->whereHas('estudiante', function ($q) use ($seccion) {
                $q->where('seccion', $seccion);
            });
        }

        // Filtro solo revisiones pendientes
        if ($request->filled('pendiente') && $request->pendiente == 1) {
            $query->whereHas('justificacion', function ($q) {
                $q->where('estado', 'Pendiente');
            });
        }

        $asistencias = $query->get();

        return view('asistencias.index', compact('asistencias', 'grados', 'secciones'));
    }


    public function escanear()
    {
        return view('asistencias.escanear');
    }

    public function registrar(Request $request)
    {
        $codigo = $request->codigo_qr; // QR escaneado


        // Buscar estudiante
        $estudiante = Estudiante::where('codigo_qr', 'LIKE', "%$codigo%")->first();
        if (!$estudiante) {
            return redirect()->route('asistencia.escanear')->with('error', 'QR no válido');
        }


        // Fecha y hora local
        $now = Carbon::now('America/Lima');
        $fechaActual = $now->toDateString();
        $horaActual = $now->format('H:i');


        if ($horaActual < '07:00') {

            return redirect()->route('asistencia.escanear')->with('error', '❌ No es horario permitido de ingreso');
        }

        // Evitar duplicados en el mismo día
        $yaRegistrado = Asistencia::where('Id_estudiante', $estudiante->Id_estudiante)
            ->whereDate('fecha', $fechaActual)
            ->first();

        if ($yaRegistrado) {


            if ($yaRegistrado->duplicado_ == 0) {
                $yaRegistrado->duplicado_ = 1;
                $yaRegistrado->save();
            }

            return redirect()->route('asistencia.exitosa', [
                'id' => $yaRegistrado->ID_asistencia,
                'duplicado' => 1
            ]);
        }

        // Determinar estado
        if ($horaActual >= '07:00' && $horaActual < '08:00') {
            $estado = 'PUNTUAL';
        } elseif ($horaActual >= '08:00' && $horaActual < '13:00') {
            $estado = 'TARDANZA';
        } else {
            $estado = 'FALTA';
        }


        // Guardar asistencia
        $asistencia = Asistencia::create([
            'fecha' => $fechaActual,
            'hora_escaneo' => $horaActual,
            'estado_Asist' => $estado,
            'duplicado_' => 0,
            'Id_estudiante' => $estudiante->Id_estudiante,
            'fecha_registro' => $now->toDateTimeString(),
            'id_usuario' => session('usuario_id') ?? 1
        ]);



        return redirect()->route('asistencia.exitosa', ['id' => $asistencia->ID_asistencia]);
    }

    public function exitosa(Request $request, $id)
    {
        $asistencia = Asistencia::findOrFail($id);

        Carbon::setLocale('es');
        $fechaFormateada = Carbon::parse($asistencia->fecha)->locale('es')->isoFormat('D [de] MMMM [de] YYYY');
        $horaFormateada = Carbon::parse($asistencia->hora_escaneo)->locale('es')->isoFormat('h:mm a');

        $duplicado = $request->duplicado ?? 0;

        return view('asistencias.exitosa', compact('asistencia', 'fechaFormateada', 'horaFormateada', 'duplicado'));
    }

    public function resumenPorEstudiante($id)
    {
        $estudiante = Estudiante::findOrFail($id);

        $totalDias = Asistencia::where('Id_estudiante', $id)->count();
        $puntual = Asistencia::where('Id_estudiante', $id)->where('estado_Asist', 'PUNTUAL')->count();
        $tardanza = Asistencia::where('Id_estudiante', $id)->where('estado_Asist', 'TARDANZA')->count();
        $falta = Asistencia::where('Id_estudiante', $id)->where('estado_Asist', 'FALTA')->count();

        $asistenciaTotal = $puntual + $tardanza;

        $porcAsistencia = $totalDias > 0 ? round(($asistenciaTotal / $totalDias) * 100, 2) : 0;
        $porcPuntualidad = $totalDias > 0 ? round(($puntual / $totalDias) * 100, 2) : 0;
        $porcTardanza = $totalDias > 0 ? round(($tardanza / $totalDias) * 100, 2) : 0;
        $porcAusentismo = $totalDias > 0 ? round(($falta / $totalDias) * 100, 2) : 0;

        return view('asistencias.resumen', compact(
            'estudiante',
            'totalDias',
            'puntual',
            'tardanza',
            'falta',
            'porcAsistencia',
            'porcPuntualidad',
            'porcTardanza',
            'porcAusentismo'
        ));
    }
}
