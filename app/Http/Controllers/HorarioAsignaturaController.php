<?php

namespace App\Http\Controllers;

use App\Models\HorarioAsignatura;
use App\Models\Profesor;
use Illuminate\Http\Request;

class HorarioAsignaturaController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_grupo' => 'required|exists:Grupos,id_grupo',
            'id_asignatura' => [
                'required',
                'exists:Asignaturas,id_asignatura',
                \Illuminate\Validation\Rule::unique('Horarios_Asignatura')->where(function ($query) use ($request) {
                    return $query->where('id_grupo', $request->id_grupo);
                })
            ],
            'horario' => 'required|string|max:100',
            'horas_lectivas' => 'required|integer|min:1',
            'sesion_zoom' => 'nullable|max:255',
        ], [
            'id_asignatura.unique' => 'La asignatura ya está programada en este grupo.',
        ]);

        HorarioAsignatura::create($validated);
        return back()->with('success', 'Asignatura programada exitosamente en el grupo.');
    }

    public function show(HorarioAsignatura $horario)
    {
        $horario->load('grupo.semestre.posgrado', 'asignatura', 'profesores');
        $profesores = Profesor::whereNotIn('id_profesor', $horario->profesores->pluck('id_profesor'))->get();
        
        return view('horarios.show', compact('horario', 'profesores'));
    }

    public function destroy(HorarioAsignatura $horario)
    {
        \DB::transaction(function () use ($horario) {
            $grupo = $horario->grupo;
            if ($grupo) {
                $alumnoIds = $grupo->alumnos->pluck('id_alumno')->toArray();
                if (!empty($alumnoIds)) {
                    \App\Models\Calificacion::whereIn('id_alumno', $alumnoIds)
                        ->where('id_asignatura', $horario->id_asignatura)
                        ->delete();
                }
            }

            // Eliminar asignaciones de profesores
            \DB::table('Asignaciones_Profesores')->where('id_horario_asignatura', $horario->id_horario_asignatura)->delete();

            // Eliminar el horario de la asignatura
            $horario->delete();
        });

        return back()->with('success', 'Asignatura y sus datos relacionados (calificaciones y asignaciones docentes) han sido eliminados exitosamente.');
    }
}
