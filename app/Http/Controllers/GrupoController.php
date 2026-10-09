<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\Alumno;
use App\Models\Grupo;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_semestre' => 'required|exists:Semestres,id_semestre',
            'nombre_grupo' => 'required|string|max:50',
            'periodo_intersemestral' => 'nullable|string|max:100',
        ]);

        Grupo::create($validated);
        return back()->with('success', 'Grupo agregado exitosamente.');
    }

    public function update(Request $request, Grupo $grupo)
    {
        $validated = $request->validate([
            'nombre_grupo' => 'required|string|max:50',
            'periodo_intersemestral' => 'nullable|string|max:100',
        ]);

        $grupo->update($validated);
        return back()->with('success', 'Grupo actualizado exitosamente.');
    }

    public function show(Grupo $grupo)
    {
        $grupo->load('semestre.posgrado', 'alumnos', 'horariosAsignaturas.asignatura', 'horariosAsignaturas.profesores');
        
        $assignedAsignaturaIds = $grupo->horariosAsignaturas->pluck('id_asignatura')->unique();
        $asignaturas = Asignatura::whereNotIn('id_asignatura', $assignedAsignaturaIds)->get();
        
        $alumnos = Alumno::whereNotIn('id_alumno', $grupo->alumnos->pluck('id_alumno'))->get();

        // Calculate student completion status for this group
        $grupoAsignaturasIds = $grupo->horariosAsignaturas->pluck('id_asignatura')->unique()->toArray();
        $grupoAlumnos = $grupo->alumnos;

        if (!empty($grupoAsignaturasIds) && !$grupoAlumnos->isEmpty()) {
            $alumnoIds = $grupoAlumnos->pluck('id_alumno')->toArray();
            $calificaciones = \App\Models\Calificacion::whereIn('id_alumno', $alumnoIds)
                ->whereIn('id_asignatura', $grupoAsignaturasIds)
                ->whereNotNull('calificacion')
                ->where('calificacion', '!=', '')
                ->get()
                ->groupBy('id_alumno');

            foreach ($grupoAlumnos as $alumno) {
                $alumnoCalificacionesCount = isset($calificaciones[$alumno->id_alumno]) 
                    ? $calificaciones[$alumno->id_alumno]->pluck('id_asignatura')->unique()->count() 
                    : 0;

                $alumno->is_finalizado = ($alumnoCalificacionesCount === count($grupoAsignaturasIds));
                $alumno->has_calificaciones_in_group = ($alumnoCalificacionesCount > 0);
            }
        } else {
            foreach ($grupoAlumnos as $alumno) {
                $alumno->is_finalizado = false;
                $alumno->has_calificaciones_in_group = false;
            }
        }

        // Sort: En curso first (false), then Finalizado (true), and alphabetically by name within each group
        $sortedAlumnos = $grupoAlumnos->sort(function ($a, $b) {
            if ($a->is_finalizado !== $b->is_finalizado) {
                return $a->is_finalizado ? 1 : -1;
            }
            $nameA = mb_strtolower($a->nombre . ' ' . $a->apellido, 'UTF-8');
            $nameB = mb_strtolower($b->nombre . ' ' . $b->apellido, 'UTF-8');
            return strcmp($nameA, $nameB);
        })->values();

        $grupo->setRelation('alumnos', $sortedAlumnos);
        
        // Count related records
        $alumnosCount = $grupoAlumnos->count();
        $asignaturasCount = $grupo->horariosAsignaturas->count();
        $alumnoIds = $grupoAlumnos->pluck('id_alumno')->toArray();
        $calificacionesCount = 0;
        if (!empty($grupoAsignaturasIds) && !empty($alumnoIds)) {
            $calificacionesCount = \App\Models\Calificacion::whereIn('id_alumno', $alumnoIds)
                ->whereIn('id_asignatura', $grupoAsignaturasIds)
                ->count();
        }
        
        return view('grupos.show', compact('grupo', 'asignaturas', 'alumnos', 'alumnosCount', 'asignaturasCount', 'calificacionesCount'));
    }

    public function destroy(Grupo $grupo)
    {
        $idSemestre = $grupo->id_semestre;
        
        \DB::transaction(function () use ($grupo) {
            $alumnoIds = $grupo->alumnos->pluck('id_alumno')->toArray();
            $asignaturaIds = $grupo->horariosAsignaturas->pluck('id_asignatura')->unique()->toArray();
            
            // 1. Delete calificaciones
            if (!empty($alumnoIds) && !empty($asignaturaIds)) {
                \App\Models\Calificacion::whereIn('id_alumno', $alumnoIds)
                    ->whereIn('id_asignatura', $asignaturaIds)
                    ->delete();
            }
            
            // 2. Delete teacher assignments (Asignaciones_Profesores)
            $horarioIds = $grupo->horariosAsignaturas->pluck('id_horario_asignatura')->toArray();
            if (!empty($horarioIds)) {
                \DB::table('Asignaciones_Profesores')->whereIn('id_horario_asignatura', $horarioIds)->delete();
            }
            
            // 3. Delete horarios (subjects scheduled in the group)
            $grupo->horariosAsignaturas()->delete();
            
            // 4. Detach students (Alumnos_Grupos)
            $grupo->alumnos()->detach();
            
            // 5. Delete the group
            $grupo->delete();
        });

        $semestre = \App\Models\Semestre::find($idSemestre);
        if ($semestre) {
            return redirect()->route('semestres.show', $semestre)->with('success', 'El grupo y todos sus datos relacionados (alumnos inscritos, asignaturas y calificaciones) han sido eliminados exitosamente.');
        }

        return redirect()->route('dashboard')->with('success', 'Grupo eliminado exitosamente.');
    }
}
