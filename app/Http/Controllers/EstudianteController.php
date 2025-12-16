<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Barryvdh\DomPDF\Facade\Pdf;


class EstudianteController extends Controller
{

    public function index(Request $request)
    {
        // Filtros recibidos
        $estado = $request->estado;
        $grado = $request->grado;
        $seccion = $request->seccion;
        $search = $request->search;

        // Listado de grados y secciones para los filtros
        $grados = ['Primero', 'Segundo', 'Tercero', 'Cuarto', 'Quinto'];
        $secciones = ['A', 'B', 'C', 'D', 'E', 'F'];

        // Query principal
        $estudiantes = Estudiante::query();

        if ($estado) {
            $estudiantes->where('estado', $estado);
        }

        if ($grado) {
            $estudiantes->where('grado', $grado);
        }

        if ($seccion) {
            $estudiantes->where('seccion', $seccion);
        }

        if ($search) {
            $estudiantes->where(function ($q) use ($search) {
                $q->where('nombres', 'LIKE', "%$search%")
                    ->orWhere('apellidos', 'LIKE', "%$search%")
                    ->orWhere('dni', 'LIKE', "%$search%");
            });
        }

        $estudiantes = $estudiantes->orderBy('apellidos')->paginate(10);

        return view('estudiantes.index', compact('estudiantes', 'estado', 'grados', 'secciones'));
    }


    public function create()
    {
        $distritos = DB::table('DISTRITO')->get();
        return view('estudiantes.create', compact('distritos'));
    }

    public function store(Request $request)
    {
        // Validación
        $request->validate([
            'dni' => 'required|max:8',
            'nombres' => 'required',
            'apellidos' => 'required',
            'edad' => 'required|numeric',
            'genero' => 'required',
            'telefono' => 'required|max:9',
            'direccion' => 'required',
            'id_distrito' => 'required|integer',
            'grado' => 'required',
            'seccion' => 'required',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Generar UUID único
        $uuid = Str::uuid()->toString();

        // Crear QR
        $qr = QrCode::create($uuid)
            ->setSize(200);

        $writer = new PngWriter();
        $qrResult = $writer->write($qr);

        // Nombre y ruta del archivo
        $fileName = 'qrcodes/' . $uuid . '.png';

        // Guardar en storage/app/public/qrcodes
        Storage::disk('public')->put($fileName, $qrResult->getString());

        // Guardar foto si existe
        $fotoPath = null;

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('estudiantes', 'public');
        }
        // Crear estudiante
        $estudiante = Estudiante::create([
            'dni' => $request->dni,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'edad' => $request->edad,
            'genero' => $request->genero,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'id_distrito' => $request->id_distrito,
            'grado' => $request->grado,
            'seccion' => $request->seccion,
            'estado' => 'Activo',
            'discapacidad' => $request->discapacidad ? 1 : 0,
            'tipo_discap' => $request->tipo_discap,
            'codigo_qr' => $fileName,
            'foto' => $fotoPath,
            'fecha_registro' => Carbon::now(),
        ]);
        return redirect()->route('estudiantes.exito', $estudiante->Id_estudiante);
    }


    public function show($id)
    {
        $estudiante = Estudiante::findOrFail($id);
        return view('estudiantes.show', compact('estudiante'));
    }
    public function edit($id)
    {
        $estudiante = Estudiante::findOrFail($id);
        $distritos = DB::table('distrito')->get();

        return view('estudiantes.edit', compact('estudiante', 'distritos'));
    }
    public function update(Request $request, $id)
    {
        $estudiante = Estudiante::findOrFail($id);

        $request->validate([
            'nombres' => 'required',
            'apellidos' => 'required',
            'telefono' => 'required|max:9',
            'direccion' => 'required',
            'grado' => 'required',
            'seccion' => 'required',
            'estado' => 'required|in:Activo,Retirado',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 📷 actualizar foto si cambia
        if ($request->hasFile('foto')) {
            if ($estudiante->foto) {
                Storage::disk('public')->delete($estudiante->foto);
            }
            $estudiante->foto = $request->file('foto')->store('estudiantes', 'public');
        }

        // 🔄 actualizar datos
        $estudiante->update([
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'grado' => $request->grado,
            'seccion' => $request->seccion,
            'estado' => $request->estado, // 👈 Activo / Retirado
            'discapacidad' => $request->discapacidad ? 1 : 0,
            'tipo_discap' => $request->tipo_discap,
        ]);

        return redirect()
            ->route('estudiantes.index')
            ->with('success', 'Estudiante actualizado correctamente');
    }

    // 🗑 ELIMINAR (OPCIONAL)
    public function destroy($id)
    {
        $estudiante = Estudiante::findOrFail($id);

        $estudiante->estado = 'Retirado';
        $estudiante->save();

        return redirect()->route('estudiantes.index')
            ->with('success', 'El estudiante fue marcado como RETIRADO');
    }


    public function exito($id)
    {
        // Busca por la PK tal como la tienes en la BD
        $estudiante = Estudiante::findOrFail($id);

        // Devuelve la vista de "éxito" pasando el estudiante
        return view('estudiantes.exito', compact('estudiante'));
    }




    public function generarPDF($id)
    {
        $estudiante = Estudiante::findOrFail($id);

        $pdf = Pdf::loadView('estudiantes.pdf-estudiante', compact('estudiante'));

        return $pdf->download('estudiante_' . $estudiante->dni . '.pdf');
    }
}
