<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Grupo;
use App\Models\HorarioAsignatura;
use App\Exports\AlumnosExport;
use App\Exports\ProfesoresExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use App\Mail\AsignaturasAlumnoMail;
use App\Mail\AsignaturasProfesorMail;
use App\Mail\KardexAlumnoMail;
use App\Exports\KardexExport;

class ConsultaController extends Controller
{
    public function alumnosPorGrupo(Request $request)
    {
        $grupos = Grupo::with(['semestre.posgrado'])->get();
        $grupoSeleccionado = null;
        $alumnos = collect();

        if ($request->has('grupo_id') && $request->grupo_id != '') {
            $grupoSeleccionado = Grupo::with(['alumnos', 'semestre.posgrado', 'horariosAsignaturas'])->findOrFail($request->grupo_id);
            $alumnos = $grupoSeleccionado->alumnos;
            
            $this->calculateAlumnosStatus($grupoSeleccionado, $alumnos);
            
            \App\Models\Bitacora::logEvent('CONSULTAR', 'Consulta Alumnos-Grupo', $grupoSeleccionado->id_grupo, ['grupo' => $grupoSeleccionado->nombre_grupo]);
        }

        return view('consultas.alumnos_grupo', compact('grupos', 'grupoSeleccionado', 'alumnos'));
    }

    public function profesoresPorAsignatura(Request $request)
    {
        $horarios = HorarioAsignatura::with(['asignatura', 'grupo.semestre.posgrado'])->get();
        $horarioSeleccionado = null;
        $profesores = collect();

        if ($request->has('horario_id') && $request->horario_id != '') {
            $horarioSeleccionado = HorarioAsignatura::with(['profesores', 'asignatura', 'grupo.semestre.posgrado'])->findOrFail($request->horario_id);
            $profesores = $horarioSeleccionado->profesores;
            
            \App\Models\Bitacora::logEvent('CONSULTAR', 'Consulta Profesores-Asignatura', $horarioSeleccionado->id_horario_asignatura, ['asignatura' => $horarioSeleccionado->asignatura->nombre_asignatura]);
        }

        return view('consultas.profesores_asignatura', compact('horarios', 'horarioSeleccionado', 'profesores'));
    }

    public function alumnosPorAsignatura(Request $request)
    {
        $horarios = HorarioAsignatura::with(['asignatura', 'grupo.semestre.posgrado'])->get();
        $horarioSeleccionado = null;
        $alumnos = collect();

        if ($request->has('horario_id') && $request->horario_id != '') {
            $horarioSeleccionado = HorarioAsignatura::with(['asignatura', 'grupo.alumnos', 'grupo.semestre.posgrado'])->findOrFail($request->horario_id);
            if ($horarioSeleccionado->grupo) {
                $alumnos = $horarioSeleccionado->grupo->alumnos;
            }
            
            \App\Models\Bitacora::logEvent('CONSULTAR', 'Consulta Alumnos-Asignatura', $horarioSeleccionado->id_horario_asignatura, ['asignatura' => $horarioSeleccionado->asignatura->nombre_asignatura]);
        }

        return view('consultas.alumnos_asignatura', compact('horarios', 'horarioSeleccionado', 'alumnos'));
    }

    public function exportAlumnosPorGrupo(Request $request)
    {
        if (!$request->has('grupo_id') || $request->grupo_id == '') {
            return back()->with('error', 'Selecciona un grupo para exportar.');
        }

        $grupo = Grupo::with(['alumnos', 'horariosAsignaturas'])->findOrFail($request->grupo_id);
        $alumnos = $grupo->alumnos;

        $this->calculateAlumnosStatus($grupo, $alumnos);
        
        \App\Models\Bitacora::logEvent('EXPORTAR EXCEL', 'Consulta Alumnos-Grupo', $grupo->id_grupo, ['grupo' => $grupo->nombre_grupo]);

        return Excel::download(new AlumnosExport($alumnos), 'Alumnos_Grupo_' . $grupo->nombre_grupo . '.xlsx');
    }

    public function exportProfesoresPorAsignatura(Request $request)
    {
        if (!$request->has('horario_id') || $request->horario_id == '') {
            return back()->with('error', 'Selecciona una asignatura impartida para exportar.');
        }

        $horario = HorarioAsignatura::with(['profesores', 'asignatura', 'grupo'])->findOrFail($request->horario_id);
        
        \App\Models\Bitacora::logEvent('EXPORTAR EXCEL', 'Consulta Profesores-Asignatura', $horario->id_horario_asignatura, ['asignatura' => $horario->asignatura->nombre_asignatura]);

        return Excel::download(new ProfesoresExport($horario->profesores), 'Profesores_' . $horario->asignatura->nombre_asignatura . '.xlsx');
    }

    public function exportAlumnosPorAsignatura(Request $request)
    {
        if (!$request->has('horario_id') || $request->horario_id == '') {
            return back()->with('error', 'Selecciona una asignatura impartida para exportar.');
        }

        $horario = HorarioAsignatura::with(['asignatura', 'grupo.alumnos'])->findOrFail($request->horario_id);
        
        \App\Models\Bitacora::logEvent('EXPORTAR EXCEL', 'Consulta Alumnos-Asignatura', $horario->id_horario_asignatura, ['asignatura' => $horario->asignatura->nombre_asignatura]);

        return Excel::download(new AlumnosExport($horario->grupo->alumnos ?? collect()), 'Alumnos_' . $horario->asignatura->nombre_asignatura . '.xlsx');
    }

    public function asignaturasPorGrupo(Request $request)
    {
        $grupos = Grupo::with(['semestre.posgrado'])->get();
        $grupoSeleccionado = null;
        $horarios = collect();

        if ($request->has('grupo_id') && $request->grupo_id != '') {
            $grupoSeleccionado = Grupo::with(['horariosAsignaturas.asignatura', 'horariosAsignaturas.profesores', 'semestre.posgrado'])->findOrFail($request->grupo_id);
            $horarios = $grupoSeleccionado->horariosAsignaturas;
            
            \App\Models\Bitacora::logEvent('CONSULTAR', 'Consulta Asignaturas-Grupo', $grupoSeleccionado->id_grupo, ['grupo' => $grupoSeleccionado->nombre_grupo]);
        }

        return view('consultas.asignaturas_grupo', compact('grupos', 'grupoSeleccionado', 'horarios'));
    }

    public function exportAsignaturasPorGrupo(Request $request)
    {
        if (!$request->has('grupo_id') || $request->grupo_id == '') {
            return back()->with('error', 'Selecciona un grupo para exportar.');
        }

        $grupo = Grupo::with(['horariosAsignaturas.asignatura', 'horariosAsignaturas.profesores'])->findOrFail($request->grupo_id);
        
        \App\Models\Bitacora::logEvent('EXPORTAR EXCEL', 'Consulta Asignaturas-Grupo', $grupo->id_grupo, ['grupo' => $grupo->nombre_grupo]);

        return Excel::download(new \App\Exports\AsignaturasExport($grupo->horariosAsignaturas), 'Asignaturas_Grupo_' . str_replace(' ', '_', $grupo->nombre_grupo) . '.xlsx');
    }

    public function asignaturasPorAlumno(Request $request)
    {
        $alumnos = \App\Models\Alumno::orderBy('nombre')->get();
        $alumnoSeleccionado = null;
        $horarios = collect();

        if ($request->has('alumno_id') && $request->alumno_id != '') {
            $alumnoSeleccionado = \App\Models\Alumno::with(['grupos.horariosAsignaturas.asignatura', 'grupos.horariosAsignaturas.profesores', 'grupos.semestre.posgrado'])->findOrFail($request->alumno_id);
            $horarios = $this->getUngradedHorarios($alumnoSeleccionado);
            
            \App\Models\Bitacora::logEvent('CONSULTAR', 'Consulta Asignaturas-Alumno', $alumnoSeleccionado->id_alumno, ['alumno' => $alumnoSeleccionado->nombre . ' ' . $alumnoSeleccionado->apellido]);
        }

        return view('consultas.asignaturas_alumno', compact('alumnos', 'alumnoSeleccionado', 'horarios'));
    }

    public function exportAsignaturasPorAlumno(Request $request)
    {
        if (!$request->has('alumno_id') || $request->alumno_id == '') {
            return back()->with('error', 'Selecciona un alumno para exportar.');
        }

        $alumno = \App\Models\Alumno::with(['grupos.horariosAsignaturas.asignatura', 'grupos.horariosAsignaturas.profesores'])->findOrFail($request->alumno_id);
        $horarios = $this->getUngradedHorarios($alumno);
        
        \App\Models\Bitacora::logEvent('EXPORTAR EXCEL', 'Consulta Asignaturas-Alumno', $alumno->id_alumno, ['alumno' => $alumno->nombre . ' ' . $alumno->apellido]);

        return Excel::download(new \App\Exports\AsignaturasExport($horarios), 'Asignaturas_Alumno_' . str_replace(' ', '_', $alumno->nombre . '_' . $alumno->apellido) . '.xlsx');
    }

    public function asignaturasPorProfesor(Request $request)
    {
        $profesores = \App\Models\Profesor::orderBy('nombre')->get();
        $profesorSeleccionado = null;
        $horarios = collect();

        if ($request->has('profesor_id') && $request->profesor_id != '') {
            $profesorSeleccionado = \App\Models\Profesor::with(['horariosAsignaturas.asignatura', 'horariosAsignaturas.grupo.alumnos'])->findOrFail($request->profesor_id);
            $horarios = $profesorSeleccionado->horariosAsignaturas;
            
            \App\Models\Bitacora::logEvent('CONSULTAR', 'Consulta Asignaturas-Profesor', $profesorSeleccionado->id_profesor, ['profesor' => $profesorSeleccionado->nombre . ' ' . $profesorSeleccionado->apellido]);
        }

        return view('consultas.asignaturas_profesor', compact('profesores', 'profesorSeleccionado', 'horarios'));
    }

    public function exportAsignaturasPorProfesor(Request $request)
    {
        if (!$request->has('profesor_id') || $request->profesor_id == '') {
            return back()->with('error', 'Selecciona un profesor para exportar.');
        }

        $profesor = \App\Models\Profesor::with(['horariosAsignaturas.asignatura', 'horariosAsignaturas.grupo.alumnos'])->findOrFail($request->profesor_id);
        $horarios = $profesor->horariosAsignaturas;
        
        \App\Models\Bitacora::logEvent('EXPORTAR EXCEL', 'Consulta Asignaturas-Profesor', $profesor->id_profesor, ['profesor' => $profesor->nombre . ' ' . $profesor->apellido]);

        return Excel::download(new \App\Exports\AsignaturasProfesorExport($horarios), 'Asignaturas_Profesor_' . str_replace(' ', '_', $profesor->nombre . '_' . $profesor->apellido) . '.xlsx');
    }

    public function sendEmailAsignaturasPorAlumno(Request $request)
    {
        if (!$request->has('alumno_id') || $request->alumno_id == '') {
            return back()->with('error', 'Selecciona un alumno para enviar el correo.');
        }

        $alumno = \App\Models\Alumno::with(['grupos.horariosAsignaturas.asignatura', 'grupos.horariosAsignaturas.profesores'])->findOrFail($request->alumno_id);
        $horarios = $this->getUngradedHorarios($alumno);
        
        if (!$alumno->correo_electronico) {
            return back()->with('error', 'El alumno seleccionado no tiene un correo electrónico registrado.');
        }

        Mail::to($alumno->correo_electronico)->send(new AsignaturasAlumnoMail($alumno, $horarios));
        
        \App\Models\Bitacora::logEvent('ENVIAR CORREO', 'Consulta Asignaturas-Alumno', $alumno->id_alumno, ['alumno' => $alumno->nombre . ' ' . $alumno->apellido]);

        return back()->with('success', 'Correo enviado exitosamente a ' . $alumno->correo_electronico);
    }

    private function getUngradedHorarios(\App\Models\Alumno $alumno)
    {
        // Obtener ids de asignaturas que ya tienen calificación registrada para este alumno
        $calificadasIds = \App\Models\Calificacion::where('id_alumno', $alumno->id_alumno)
            ->whereNotNull('calificacion')
            ->where('calificacion', '!=', '')
            ->pluck('id_asignatura')
            ->toArray();

        // Filtrar horarios de los grupos del alumno
        return $alumno->grupos->flatMap->horariosAsignaturas->filter(function ($horario) use ($calificadasIds) {
            return !in_array($horario->id_asignatura, $calificadasIds);
        })->values();
    }

    private function calculateAlumnosStatus($grupo, $alumnos)
    {
        $grupoAsignaturasIds = $grupo->horariosAsignaturas->pluck('id_asignatura')->unique()->toArray();
        
        if (!empty($grupoAsignaturasIds)) {
            $alumnoIds = $alumnos->pluck('id_alumno')->toArray();
            $calificaciones = \App\Models\Calificacion::whereIn('id_alumno', $alumnoIds)
                ->whereIn('id_asignatura', $grupoAsignaturasIds)
                ->whereNotNull('calificacion')
                ->where('calificacion', '!=', '')
                ->get()
                ->groupBy('id_alumno');

            foreach ($alumnos as $alumno) {
                $alumnoCalificacionesCount = isset($calificaciones[$alumno->id_alumno]) 
                    ? $calificaciones[$alumno->id_alumno]->pluck('id_asignatura')->unique()->count() 
                    : 0;

                $alumno->is_finalizado = ($alumnoCalificacionesCount === count($grupoAsignaturasIds));
            }
        } else {
            foreach ($alumnos as $alumno) {
                $alumno->is_finalizado = false;
            }
        }
    }

    public function sendEmailAsignaturasPorProfesor(Request $request)
    {
        if (!$request->has('profesor_id') || $request->profesor_id == '') {
            return back()->with('error', 'Selecciona un profesor para enviar el correo.');
        }

        $profesor = \App\Models\Profesor::with(['horariosAsignaturas.asignatura', 'horariosAsignaturas.grupo.alumnos'])->findOrFail($request->profesor_id);
        $horarios = $profesor->horariosAsignaturas;
        
        if (!$profesor->correo_electronico) {
            return back()->with('error', 'El profesor seleccionado no tiene un correo electrónico registrado.');
        }

        Mail::to($profesor->correo_electronico)->send(new AsignaturasProfesorMail($profesor, $horarios));
        
        \App\Models\Bitacora::logEvent('ENVIAR CORREO', 'Consulta Asignaturas-Profesor', $profesor->id_profesor, ['profesor' => $profesor->nombre . ' ' . $profesor->apellido]);

        return back()->with('success', 'Correo enviado exitosamente a ' . $profesor->correo_electronico);
    }

    public function kardexAlumno(Request $request)
    {
        $alumnos = \App\Models\Alumno::orderBy('nombre')->get();
        $alumnoSeleccionado = null;
        $horarios = collect();

        if ($request->has('alumno_id') && $request->alumno_id != '') {
            $alumnoSeleccionado = \App\Models\Alumno::with(['grupos.horariosAsignaturas.asignatura', 'grupos.horariosAsignaturas.profesores', 'grupos.semestre.posgrado'])->findOrFail($request->alumno_id);
            $horarios = $alumnoSeleccionado->grupos->flatMap->horariosAsignaturas;

            // Load student's grades
            $calificaciones = \App\Models\Calificacion::where('id_alumno', $alumnoSeleccionado->id_alumno)
                ->get()
                ->keyBy('id_asignatura');

            // Attach grade value to schedules
            foreach ($horarios as $horario) {
                $horario->calificacion = isset($calificaciones[$horario->id_asignatura]) 
                    ? $calificaciones[$horario->id_asignatura]->calificacion 
                    : 'Sin Calificación';
            }

            \App\Models\Bitacora::logEvent('CONSULTAR', 'Kardex Alumno', $alumnoSeleccionado->id_alumno, ['alumno' => $alumnoSeleccionado->nombre . ' ' . $alumnoSeleccionado->apellido]);
        }

        return view('consultas.kardex_alumno', compact('alumnos', 'alumnoSeleccionado', 'horarios'));
    }

    public function exportKardexAlumno(Request $request)
    {
        if (!$request->has('alumno_id') || $request->alumno_id == '') {
            return back()->with('error', 'Selecciona un alumno para exportar.');
        }

        $alumno = \App\Models\Alumno::with(['grupos.horariosAsignaturas.asignatura'])->findOrFail($request->alumno_id);
        $horarios = $alumno->grupos->flatMap->horariosAsignaturas;

        // Load student's grades
        $calificaciones = \App\Models\Calificacion::where('id_alumno', $alumno->id_alumno)
            ->get()
            ->keyBy('id_asignatura');

        foreach ($horarios as $horario) {
            $horario->calificacion = isset($calificaciones[$horario->id_asignatura]) 
                ? $calificaciones[$horario->id_asignatura]->calificacion 
                : 'Sin Calificación';
        }

        \App\Models\Bitacora::logEvent('EXPORTAR EXCEL', 'Kardex Alumno', $alumno->id_alumno, ['alumno' => $alumno->nombre . ' ' . $alumno->apellido]);

        return Excel::download(new KardexExport($horarios), 'Kardex_Alumno_' . str_replace(' ', '_', $alumno->nombre . '_' . $alumno->apellido) . '.xlsx');
    }

    public function sendEmailKardexAlumno(Request $request)
    {
        if (!$request->has('alumno_id') || $request->alumno_id == '') {
            return back()->with('error', 'Selecciona un alumno para enviar el correo.');
        }

        $alumno = \App\Models\Alumno::with(['grupos.horariosAsignaturas.asignatura'])->findOrFail($request->alumno_id);
        $horarios = $alumno->grupos->flatMap->horariosAsignaturas;

        if (!$alumno->correo_electronico) {
            return back()->with('error', 'El alumno seleccionado no tiene un correo electrónico registrado.');
        }

        // Load student's grades
        $calificaciones = \App\Models\Calificacion::where('id_alumno', $alumno->id_alumno)
            ->get()
            ->keyBy('id_asignatura');

        foreach ($horarios as $horario) {
            $horario->calificacion = isset($calificaciones[$horario->id_asignatura]) 
                ? $calificaciones[$horario->id_asignatura]->calificacion 
                : 'Sin Calificación';
        }

        Mail::to($alumno->correo_electronico)->send(new KardexAlumnoMail($alumno, $horarios));

        \App\Models\Bitacora::logEvent('ENVIAR CORREO', 'Kardex Alumno', $alumno->id_alumno, ['alumno' => $alumno->nombre . ' ' . $alumno->apellido]);

        return back()->with('success', 'Correo de Kardex enviado exitosamente a ' . $alumno->correo_electronico);
    }
}
