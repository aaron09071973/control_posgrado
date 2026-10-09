<?php

namespace App\Http\Controllers;

use App\Models\Profesor;
use Illuminate\Http\Request;

class ProfesorController extends Controller
{
    public function index()
    {
        $profesores = Profesor::with(['horariosAsignaturas.asignatura', 'horariosAsignaturas.grupo.semestre.posgrado'])->get();
        return view('profesores.index', compact('profesores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cod_profesor' => 'required|string|max:30|unique:Profesores,cod_profesor',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'correo_electronico' => 'required|email|max:150|unique:Profesores,correo_electronico',
            'telefono' => 'nullable|string|max:20',
            'adscripcion' => 'required|in:CEPHCIS,Externo',
        ]);

        Profesor::create($validated);
        return redirect()->route('profesores.index')->with('success', 'Profesor creado exitosamente.');
    }

    public function update(Request $request, Profesor $profesor)
    {
        $validated = $request->validate([
            'cod_profesor' => 'required|string|max:30|unique:Profesores,cod_profesor,' . $profesor->id_profesor . ',id_profesor',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'correo_electronico' => 'required|email|max:150|unique:Profesores,correo_electronico,' . $profesor->id_profesor . ',id_profesor',
            'telefono' => 'nullable|string|max:20',
            'adscripcion' => 'required|in:CEPHCIS,Externo',
        ]);

        $profesor->update($validated);
        return redirect()->route('profesores.index')->with('success', 'Profesor actualizado exitosamente.');
    }

    public function destroy(Profesor $profesor)
    {
        $profesor->delete();
        return redirect()->route('profesores.index')->with('success', 'Profesor eliminado exitosamente.');
    }

    public function constancia(Request $request, Profesor $profesor)
    {
        $profesor->load(['horariosAsignaturas.asignatura', 'horariosAsignaturas.grupo.semestre.posgrado']);
        
        $idHorario = $request->query('id_horario');
        if ($idHorario) {
            $horario = $profesor->horariosAsignaturas->firstWhere('id_horario_asignatura', $idHorario);
        } else {
            $horario = $profesor->horariosAsignaturas->last();
        }
        
        if (!$horario) {
            return back()->with('error', 'El profesor no tiene una asignatura asignada para generar la constancia.');
        }

        \App\Models\Bitacora::logEvent('GENERAR CONSTANCIA', 'Profesor', $profesor->id_profesor, ['cod_profesor' => $profesor->cod_profesor, 'id_horario_asignatura' => $horario->id_horario_asignatura]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('profesores.constancia', compact('profesor', 'horario'));
        
        return $pdf->stream('Constancia_Profesor_' . $profesor->cod_profesor . '.pdf');
    }
}
