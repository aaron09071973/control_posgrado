<?php

namespace App\Http\Controllers;

use App\Models\AlumnoGrupo;
use App\Models\AsignacionProfesor;
use Illuminate\Http\Request;

class AsignacionController extends Controller
{
    public function asignarAlumno(Request $request)
    {
        $validated = $request->validate([
            'id_grupo' => 'required|exists:Grupos,id_grupo',
            'id_alumno' => 'required|exists:Alumnos,id_alumno',
        ]);

        // Evitar duplicados (aunque la base de datos lo evita por el constraint UNIQUE)
        $exists = AlumnoGrupo::where('id_grupo', $validated['id_grupo'])
            ->where('id_alumno', $validated['id_alumno'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'El alumno ya está inscrito en este grupo.');
        }

        AlumnoGrupo::create($validated);
        return back()->with('success', 'Alumno asignado al grupo exitosamente.');
    }

    public function desasignarAlumno(AlumnoGrupo $asignacion)
    {
        \DB::transaction(function () use ($asignacion) {
            // Obtener las asignaturas programadas para el grupo actual
            $grupoAsignaturasIds = \App\Models\HorarioAsignatura::where('id_grupo', $asignacion->id_grupo)
                ->pluck('id_asignatura')
                ->unique()
                ->toArray();

            // Eliminar calificaciones del alumno en las asignaturas de dicho grupo
            if (!empty($grupoAsignaturasIds)) {
                \App\Models\Calificacion::where('id_alumno', $asignacion->id_alumno)
                    ->whereIn('id_asignatura', $grupoAsignaturasIds)
                    ->delete();
            }

            // Eliminar el registro de la tabla pivote
            $asignacion->delete();
        });

        return back()->with('success', 'Alumno removido del grupo y calificaciones eliminadas exitosamente.');
    }

    public function asignarProfesor(Request $request)
    {
        $validated = $request->validate([
            'id_horario_asignatura' => 'required|exists:Horarios_Asignatura,id_horario_asignatura',
            'id_profesor' => 'required|exists:Profesores,id_profesor',
        ]);

        $exists = AsignacionProfesor::where('id_horario_asignatura', $validated['id_horario_asignatura'])
            ->where('id_profesor', $validated['id_profesor'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'El profesor ya está asignado a este horario.');
        }

        AsignacionProfesor::create($validated);
        return back()->with('success', 'Profesor asignado a la asignatura exitosamente.');
    }

    public function desasignarProfesor(AsignacionProfesor $asignacion)
    {
        $asignacion->delete();
        return back()->with('success', 'Profesor removido de la asignatura exitosamente.');
    }
}
