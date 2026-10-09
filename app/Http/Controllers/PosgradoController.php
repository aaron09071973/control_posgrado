<?php

namespace App\Http\Controllers;

use App\Models\Posgrado;
use Illuminate\Http\Request;

class PosgradoController extends Controller
{
    public function index()
    {
        $posgrados = Posgrado::with('semestres.grupos')->get();
        
        foreach ($posgrados as $posgrado) {
            $semestresCount = $posgrado->semestres->count();
            $gruposCount = 0;
            $alumnosCount = 0;
            $calificacionesCount = 0;
            $profesoresCount = 0;
            
            $allGrupoIds = [];
            foreach ($posgrado->semestres as $semestre) {
                $grupoIds = $semestre->grupos->pluck('id_grupo')->toArray();
                $allGrupoIds = array_merge($allGrupoIds, $grupoIds);
            }
            $allGrupoIds = array_unique($allGrupoIds);
            $gruposCount = count($allGrupoIds);
            
            if (!empty($allGrupoIds)) {
                // Cantidad de alumnos inscritos únicos en los grupos del posgrado
                $alumnosCount = \DB::table('Alumnos_Grupos')
                    ->whereIn('id_grupo', $allGrupoIds)
                    ->distinct('id_alumno')
                    ->count();
                
                // Cantidad de asignaturas programadas (horarios) únicos
                $horarios = \App\Models\HorarioAsignatura::whereIn('id_grupo', $allGrupoIds)->get();
                $horarioIds = $horarios->pluck('id_horario_asignatura')->toArray();
                $asignaturaIds = $horarios->pluck('id_asignatura')->unique()->toArray();
                
                // Cantidad de calificaciones capturadas para los alumnos y materias de estos grupos
                $alumnoIds = \DB::table('Alumnos_Grupos')->whereIn('id_grupo', $allGrupoIds)->pluck('id_alumno')->unique()->toArray();
                if (!empty($alumnoIds) && !empty($asignaturaIds)) {
                    $calificacionesCount = \App\Models\Calificacion::whereIn('id_alumno', $alumnoIds)
                        ->whereIn('id_asignatura', $asignaturaIds)
                        ->count();
                }
                
                // Cantidad de profesores únicos asignados a estos horarios
                if (!empty($horarioIds)) {
                    $profesoresCount = \DB::table('Asignaciones_Profesores')
                        ->whereIn('id_horario_asignatura', $horarioIds)
                        ->distinct('id_profesor')
                        ->count();
                }
            }
            
            $posgrado->semestres_count = $semestresCount;
            $posgrado->grupos_count = $gruposCount;
            $posgrado->alumnos_count = $alumnosCount;
            $posgrado->calificaciones_count = $calificacionesCount;
            $posgrado->profesores_count = $profesoresCount;
        }

        return view('posgrados.index', compact('posgrados'));
    }

    public function show(Posgrado $posgrado)
    {
        $posgrado->load('semestres.grupos');
        
        foreach ($posgrado->semestres as $semestre) {
            $gruposCount = $semestre->grupos->count();
            $alumnosCount = 0;
            $asignaturasCount = 0;
            $calificacionesCount = 0;
            $profesoresCount = 0;
            
            $grupoIds = $semestre->grupos->pluck('id_grupo')->toArray();
            if (!empty($grupoIds)) {
                // Cantidad de alumnos inscritos únicos en los grupos del semestre
                $alumnosCount = \DB::table('Alumnos_Grupos')
                    ->whereIn('id_grupo', $grupoIds)
                    ->distinct('id_alumno')
                    ->count();
                
                // Cantidad de asignaturas programadas (horarios) únicos
                $horarios = \App\Models\HorarioAsignatura::whereIn('id_grupo', $grupoIds)->get();
                $asignaturasCount = $horarios->pluck('id_asignatura')->unique()->count();
                
                // Cantidad de calificaciones capturadas para los alumnos y materias de estos grupos
                $alumnoIds = \DB::table('Alumnos_Grupos')->whereIn('id_grupo', $grupoIds)->pluck('id_alumno')->unique()->toArray();
                $asignaturaIds = $horarios->pluck('id_asignatura')->unique()->toArray();
                if (!empty($alumnoIds) && !empty($asignaturaIds)) {
                    $calificacionesCount = \App\Models\Calificacion::whereIn('id_alumno', $alumnoIds)
                        ->whereIn('id_asignatura', $asignaturaIds)
                        ->count();
                }
                
                // Cantidad de profesores únicos asignados a estos horarios
                $horarioIds = $horarios->pluck('id_horario_asignatura')->toArray();
                if (!empty($horarioIds)) {
                    $profesoresCount = \DB::table('Asignaciones_Profesores')
                        ->whereIn('id_horario_asignatura', $horarioIds)
                        ->distinct('id_profesor')
                        ->count();
                }
            }
            
            $semestre->grupos_count = $gruposCount;
            $semestre->alumnos_count = $alumnosCount;
            $semestre->asignaturas_count = $asignaturasCount;
            $semestre->calificaciones_count = $calificacionesCount;
            $semestre->profesores_count = $profesoresCount;
        }

        return view('posgrados.show', compact('posgrado'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_posgrado' => 'required|string|max:150',
            'codigo_programa' => 'required|string|max:20|unique:Posgrados,codigo_programa',
        ]);

        Posgrado::create($validated);
        return redirect()->route('posgrados.index')->with('success', 'Posgrado creado exitosamente.');
    }

    public function update(Request $request, Posgrado $posgrado)
    {
        $validated = $request->validate([
            'nombre_posgrado' => 'required|string|max:150',
            'codigo_programa' => 'required|string|max:20|unique:Posgrados,codigo_programa,' . $posgrado->id_posgrado . ',id_posgrado',
        ]);

        $posgrado->update($validated);
        return redirect()->route('posgrados.index')->with('success', 'Posgrado actualizado exitosamente.');
    }

    public function destroy(Posgrado $posgrado)
    {
        \DB::transaction(function () use ($posgrado) {
            // Obtener semestres y precargar grupos con sus alumnos y horarios
            $semestres = $posgrado->semestres()->with('grupos.alumnos', 'grupos.horariosAsignaturas')->get();

            foreach ($semestres as $semestre) {
                foreach ($semestre->grupos as $grupo) {
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
                
                // 6. Eliminar el semestre
                $semestre->delete();
            }

            // 7. Finalmente, eliminar el posgrado
            $posgrado->delete();
        });

        return redirect()->route('posgrados.index')->with('success', 'El posgrado y toda la información relacionada de sus semestres, grupos, alumnos, calificaciones y profesores han sido eliminados exitosamente.');
    }
}
