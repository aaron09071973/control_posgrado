<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use App\Models\Alumno;
use App\Models\Asignatura;
use Illuminate\Http\Request;

class CalificacionController extends Controller
{
    public function index()
    {
        $calificaciones = Calificacion::with(['alumno', 'asignatura'])->get();
        $alumnos = Alumno::with(['grupos.horariosAsignaturas.asignatura'])->orderBy('nombre')->get();
        $asignaturas = Asignatura::orderBy('nombre_asignatura')->get();

        // Build a mapping of id_alumno => assigned and UNGRADED asignaturas
        $alumnoAsignaturasMap = $alumnos->mapWithKeys(function ($alumno) use ($calificaciones) {
            $calificadasIds = $calificaciones->where('id_alumno', $alumno->id_alumno)->pluck('id_asignatura')->toArray();

            $asignaturasDeAlumno = $alumno->grupos->flatMap(function ($grupo) {
                return $grupo->horariosAsignaturas->map(function ($ha) {
                    return $ha->asignatura;
                });
            })->unique('id_asignatura')
              ->filter(function ($asignatura) use ($calificadasIds) {
                  return !in_array($asignatura->id_asignatura, $calificadasIds);
              })
              ->values();

            return [$alumno->id_alumno => $asignaturasDeAlumno];
        });

        return view('calificaciones.index', compact('calificaciones', 'alumnos', 'asignaturas', 'alumnoAsignaturasMap'));
    }

    public function store(Request $request)
    {
        session()->flash('open_create_modal', true);

        $validated = $request->validate([
            'id_alumno' => 'required|exists:Alumnos,id_alumno',
            'id_asignatura' => 'required|exists:Asignaturas,id_asignatura',
            'calificacion' => 'nullable|string|max:10',
        ]);

        // Validate that the subject is assigned to this student
        $alumno = Alumno::with(['grupos.horariosAsignaturas'])->findOrFail($request->id_alumno);
        $assignedSubjectIds = $alumno->grupos->flatMap->horariosAsignaturas->pluck('id_asignatura')->unique()->toArray();

        if (!in_array($request->id_asignatura, $assignedSubjectIds)) {
            return back()->withErrors(['id_asignatura' => 'La asignatura seleccionada no está asignada a este alumno.'])->withInput();
        }

        // Validate that it is not already graded
        $alreadyGraded = Calificacion::where('id_alumno', $request->id_alumno)
            ->where('id_asignatura', $request->id_asignatura)
            ->exists();

        if ($alreadyGraded) {
            return back()->withErrors(['id_asignatura' => 'Esta asignatura ya ha sido calificada para este alumno.'])->withInput();
        }

        Calificacion::create($validated);
        return redirect()->route('calificaciones.index')
            ->with('success', 'Calificación registrada exitosamente.')
            ->with('open_create_modal', true);
    }

    public function update(Request $request, Calificacion $calificacione)
    {
        session()->flash('open_edit_modal_' . $calificacione->id_calificacion, true);

        $validated = $request->validate([
            'id_alumno' => 'required|exists:Alumnos,id_alumno',
            'id_asignatura' => 'required|exists:Asignaturas,id_asignatura',
            'calificacion' => 'nullable|string|max:10',
        ]);

        // Validate that the subject is assigned to this student
        $alumno = Alumno::with(['grupos.horariosAsignaturas'])->findOrFail($request->id_alumno);
        $assignedSubjectIds = $alumno->grupos->flatMap->horariosAsignaturas->pluck('id_asignatura')->unique()->toArray();

        if (!in_array($request->id_asignatura, $assignedSubjectIds)) {
            return back()->withErrors(['id_asignatura' => 'La asignatura seleccionada no está asignada a este alumno.'])->withInput();
        }

        // Validate that it is not already graded (except for this current row)
        $alreadyGraded = Calificacion::where('id_alumno', $request->id_alumno)
            ->where('id_asignatura', $request->id_asignatura)
            ->where('id_calificacion', '!=', $calificacione->id_calificacion)
            ->exists();

        if ($alreadyGraded) {
            return back()->withErrors(['id_asignatura' => 'Esta asignatura ya ha sido calificada para este alumno.'])->withInput();
        }

        $calificacione->update($validated);
        return redirect()->route('calificaciones.index')->with('success', 'Calificación actualizada exitosamente.');
    }

    public function destroy(Calificacion $calificacione)
    {
        $calificacione->delete();
        return redirect()->route('calificaciones.index')->with('success', 'Calificación eliminada exitosamente.');
    }
}
