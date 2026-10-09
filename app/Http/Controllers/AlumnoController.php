<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AlumnoController extends Controller
{
    public function index()
    {
        $alumnos = Alumno::with(['grupos.horariosAsignaturas'])->orderBy('nombre')->get();
        
        $alumnoIds = $alumnos->pluck('id_alumno')->toArray();
        $calificaciones = \App\Models\Calificacion::whereIn('id_alumno', $alumnoIds)
            ->whereNotNull('calificacion')
            ->where('calificacion', '!=', '')
            ->get()
            ->groupBy('id_alumno');

        foreach ($alumnos as $alumno) {
            $assignedSubjectsCount = $alumno->grupos->flatMap->horariosAsignaturas->pluck('id_asignatura')->unique()->count();
            
            $alumnoCalificacionesCount = isset($calificaciones[$alumno->id_alumno])
                ? $calificaciones[$alumno->id_alumno]->pluck('id_asignatura')->unique()->count()
                : 0;

            $alumno->is_finalizado = ($assignedSubjectsCount > 0 && $alumnoCalificacionesCount === $assignedSubjectsCount);
            $alumno->has_calificaciones = ($alumnoCalificacionesCount > 0);
        }

        return view('alumnos.index', compact('alumnos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Cta_alumno' => 'required|string|max:30|unique:Alumnos,Cta_alumno',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'correo_electronico' => 'required|email|max:150|unique:Alumnos,correo_electronico',
            'generacion' => 'required|string|max:20',
        ]);

        Alumno::create($validated);
        return redirect()->route('alumnos.index')->with('success', 'Alumno creado exitosamente.');
    }

    public function update(Request $request, Alumno $alumno)
    {
        $validated = $request->validate([
            'Cta_alumno' => 'required|string|max:30|unique:Alumnos,Cta_alumno,' . $alumno->id_alumno . ',id_alumno',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'correo_electronico' => 'required|email|max:150|unique:Alumnos,correo_electronico,' . $alumno->id_alumno . ',id_alumno',
            'generacion' => 'required|string|max:20',
        ]);

        $alumno->update($validated);
        return redirect()->route('alumnos.index')->with('success', 'Alumno actualizado exitosamente.');
    }

    public function destroy(Alumno $alumno)
    {
        \DB::transaction(function () use ($alumno) {
            // 1. Delete all calificaciones of the student
            \App\Models\Calificacion::where('id_alumno', $alumno->id_alumno)->delete();
            
            // 2. Detach all group assignments (Alumnos_Grupos)
            $alumno->grupos()->detach();
            
            // 3. Delete the student
            $alumno->delete();
        });

        return redirect()->route('alumnos.index')->with('success', 'El alumno y todos sus datos relacionados (calificaciones e inscripciones) han sido eliminados exitosamente.');
    }

    public function constancia(Alumno $alumno)
    {
        $alumno->load(['grupos.semestre.posgrado', 'grupos.horariosAsignaturas.asignatura']);
        
        $grupo = $alumno->grupos->sortBy('pivot.id_alumno_grupo')->last();
        if (!$grupo) {
            return back()->with('error', 'El alumno no tiene un grupo asignado para generar la constancia.');
        }

        \App\Models\Bitacora::logEvent('GENERAR CONSTANCIA', 'Alumno', $alumno->id_alumno, ['Cta_alumno' => $alumno->Cta_alumno]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('alumnos.constancia', compact('alumno', 'grupo'));
        
        return $pdf->stream('Constancia_' . $alumno->Cta_alumno . '.pdf');
    }
}
