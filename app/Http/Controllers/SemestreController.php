<?php

namespace App\Http\Controllers;

use App\Models\Semestre;
use Illuminate\Http\Request;

class SemestreController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_posgrado' => 'required|exists:Posgrados,id_posgrado',
            'numero_semestre' => 'required|string|max:100',
            'desc_semestre' => 'nullable|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_termino' => 'required|date|after:fecha_inicio',
        ]);

        Semestre::create($validated);
        return back()->with('success', 'Semestre agregado exitosamente.');
    }

    public function show(Semestre $semestre)
    {
        $semestre->load('posgrado', 'grupos');
        return view('semestres.show', compact('semestre'));
    }

    public function update(Request $request, Semestre $semestre)
    {
        $validated = $request->validate([
            'numero_semestre' => 'required|string|max:100',
            'desc_semestre' => 'nullable|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_termino' => 'required|date|after:fecha_inicio',
        ]);

        $semestre->update($validated);
        return back()->with('success', 'Semestre actualizado exitosamente.');
    }

    public function destroy(Semestre $semestre)
    {
        \DB::transaction(function () use ($semestre) {
            // Obtener los grupos del semestre
            $grupos = $semestre->grupos()->with(['alumnos', 'horariosAsignaturas'])->get();

            foreach ($grupos as $grupo) {
                $alumnoIds = $grupo->alumnos->pluck('id_alumno')->toArray();
                $asignaturaIds = $grupo->horariosAsignaturas->pluck('id_asignatura')->unique()->toArray();
                
                // 1. Eliminar calificaciones correspondientes a los alumnos y asignaturas de este grupo
                if (!empty($alumnoIds) && !empty($asignaturaIds)) {
                    \App\Models\Calificacion::whereIn('id_alumno', $alumnoIds)
                        ->whereIn('id_asignatura', $asignaturaIds)
                        ->delete();
                }
                
                // 2. Eliminar asignaciones de profesores para los horarios de este grupo
                $horarioIds = $grupo->horariosAsignaturas->pluck('id_horario_asignatura')->toArray();
                if (!empty($horarioIds)) {
                    \DB::table('Asignaciones_Profesores')->whereIn('id_horario_asignatura', $horarioIds)->delete();
                }
                
                // 3. Eliminar los horarios/asignaturas del grupo
                $grupo->horariosAsignaturas()->delete();
                
                // 4. Desvincular alumnos del grupo (tabla pivote Alumnos_Grupos)
                $grupo->alumnos()->detach();
                
                // 5. Eliminar el grupo
                $grupo->delete();
            }

            // Finalmente, eliminar el semestre
            $semestre->delete();
        });

        return back()->with('success', 'El semestre y toda la información relacionada de sus grupos (alumnos, asignaturas, calificaciones y profesores) han sido eliminados exitosamente.');
    }
}
