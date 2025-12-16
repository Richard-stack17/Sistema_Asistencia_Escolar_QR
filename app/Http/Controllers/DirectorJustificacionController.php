<?php

namespace App\Http\Controllers;

use App\Models\Justificacion;
use App\Models\RevisionJustificacion;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DirectorJustificacionController extends Controller
{
    public function index(Request $request)
    {
        $estado = $request->estado;

        $justificaciones = Justificacion::with(['asistencia.estudiante', 'revision'])
            ->when($estado, function ($q) use ($estado) {
                $q->where('estado', $estado);
            })

            // FILTRO POR FECHA
            ->when($request->filled('fecha'), function ($q) use ($request) {
                $q->whereHas('asistencia', function ($sub) use ($request) {
                    $sub->whereDate('fecha', $request->fecha);
                });
            })

            ->orderBy('fecha_envio', 'desc')
            ->get();

        return view('director.justificaciones', compact('justificaciones'));
    }



    public function resolver(Request $request, $id)
    {
        $request->validate([
            'accion' => 'required|in:aprobar,rechazar',
            'observacion' => 'required|string|min:5',
        ]);

        $justificacion = Justificacion::with('asistencia')->findOrFail($id);

        if ($request->accion === 'aprobar') {
            $justificacion->update(['estado' => 'Aprobado']);

            // Actualizar asistencia
            $justificacion->asistencia->update([
                'estado_Asist' => 'JUSTIFICADO'
            ]);
        } else {
            $justificacion->update(['estado' => 'Rechazado']);
        }

        RevisionJustificacion::create([
            'fecha_revision' => now(),
            'resultado' => $request->accion === 'aprobar'
                ? 'Aprobado'
                : 'Rechazado',
            'observacion' => $request->observacion,
            'id_director' => session('usuario_id'),
            'id_justificacion' => $id
        ]);

        return redirect()
            ->route('director.justificaciones')
            ->with('success', 'Justificación resuelta correctamente');
    }
}
