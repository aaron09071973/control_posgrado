<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosgradoController;
use App\Http\Controllers\SemestreController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\AsignaturaController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\ProfesorController;
use App\Http\Controllers\HorarioAsignaturaController;
use App\Http\Controllers\AsignacionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CalificacionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas de lectura (Consulta y Administrador)
    Route::get('/posgrados', [PosgradoController::class, 'index'])->name('posgrados.index');
    Route::get('/posgrados/{posgrado}', [PosgradoController::class, 'show'])->name('posgrados.show');
    
    Route::get('/semestres/{semestre}', [SemestreController::class, 'show'])->name('semestres.show');
    
    Route::get('/grupos/{grupo}', [GrupoController::class, 'show'])->name('grupos.show');
    
    Route::get('/asignaturas', [AsignaturaController::class, 'index'])->name('asignaturas.index');
    
    Route::get('/alumnos', [AlumnoController::class, 'index'])->name('alumnos.index');
    Route::get('/alumnos/{alumno}/constancia', [AlumnoController::class, 'constancia'])->name('alumnos.constancia');
    
    Route::get('/calificaciones', [CalificacionController::class, 'index'])->name('calificaciones.index');
    
    Route::get('/profesores', [ProfesorController::class, 'index'])->name('profesores.index');
    Route::get('/profesores/{profesor}/constancia', [ProfesorController::class, 'constancia'])->name('profesores.constancia');
    
    Route::get('/horarios/{horario}', [HorarioAsignaturaController::class, 'show'])->name('horarios.show');

    // Consultas
    Route::get('/consultas/alumnos-por-grupo', [\App\Http\Controllers\ConsultaController::class, 'alumnosPorGrupo'])->name('consultas.alumnos');
    Route::get('/consultas/alumnos-por-grupo/exportar', [\App\Http\Controllers\ConsultaController::class, 'exportAlumnosPorGrupo'])->name('consultas.alumnos.export');
    
    Route::get('/consultas/alumnos-por-asignatura', [\App\Http\Controllers\ConsultaController::class, 'alumnosPorAsignatura'])->name('consultas.alumnos_asignatura');
    Route::get('/consultas/alumnos-por-asignatura/exportar', [\App\Http\Controllers\ConsultaController::class, 'exportAlumnosPorAsignatura'])->name('consultas.alumnos_asignatura.export');
    
    Route::get('/consultas/profesores-por-asignatura', [\App\Http\Controllers\ConsultaController::class, 'profesoresPorAsignatura'])->name('consultas.profesores');
    Route::get('/consultas/profesores-por-asignatura/exportar', [\App\Http\Controllers\ConsultaController::class, 'exportProfesoresPorAsignatura'])->name('consultas.profesores.export');

    Route::get('/consultas/asignaturas-por-grupo', [\App\Http\Controllers\ConsultaController::class, 'asignaturasPorGrupo'])->name('consultas.asignaturas');
    Route::get('/consultas/asignaturas-por-grupo/exportar', [\App\Http\Controllers\ConsultaController::class, 'exportAsignaturasPorGrupo'])->name('consultas.asignaturas.export');

    Route::get('/consultas/asignaturas-por-alumno', [\App\Http\Controllers\ConsultaController::class, 'asignaturasPorAlumno'])->name('consultas.asignaturas_alumno');
    Route::get('/consultas/asignaturas-por-alumno/exportar', [\App\Http\Controllers\ConsultaController::class, 'exportAsignaturasPorAlumno'])->name('consultas.asignaturas_alumno.export');
    Route::post('/consultas/asignaturas-por-alumno/enviar-correo', [\App\Http\Controllers\ConsultaController::class, 'sendEmailAsignaturasPorAlumno'])->name('consultas.asignaturas_alumno.email');

    Route::get('/consultas/kardex-alumno', [\App\Http\Controllers\ConsultaController::class, 'kardexAlumno'])->name('consultas.kardex');
    Route::get('/consultas/kardex-alumno/exportar', [\App\Http\Controllers\ConsultaController::class, 'exportKardexAlumno'])->name('consultas.kardex.export');
    Route::post('/consultas/kardex-alumno/enviar-correo', [\App\Http\Controllers\ConsultaController::class, 'sendEmailKardexAlumno'])->name('consultas.kardex.email');

    Route::get('/consultas/asignaturas-por-profesor', [\App\Http\Controllers\ConsultaController::class, 'asignaturasPorProfesor'])->name('consultas.asignaturas_profesor');
    Route::get('/consultas/asignaturas-por-profesor/exportar', [\App\Http\Controllers\ConsultaController::class, 'exportAsignaturasPorProfesor'])->name('consultas.asignaturas_profesor.export');
    Route::post('/consultas/asignaturas-por-profesor/enviar-correo', [\App\Http\Controllers\ConsultaController::class, 'sendEmailAsignaturasPorProfesor'])->name('consultas.asignaturas_profesor.email');

    // Rutas de mutación (Solo Administrador)
    Route::middleware(['role:administrador'])->group(function () {
        // Posgrados
        Route::post('/posgrados', [PosgradoController::class, 'store'])->name('posgrados.store');
        Route::put('/posgrados/{posgrado}', [PosgradoController::class, 'update'])->name('posgrados.update');
        Route::delete('/posgrados/{posgrado}', [PosgradoController::class, 'destroy'])->name('posgrados.destroy');

        // Semestres
        Route::post('/semestres', [SemestreController::class, 'store'])->name('semestres.store');
        Route::put('/semestres/{semestre}', [SemestreController::class, 'update'])->name('semestres.update');
        Route::delete('/semestres/{semestre}', [SemestreController::class, 'destroy'])->name('semestres.destroy');

        // Grupos
        Route::post('/grupos', [GrupoController::class, 'store'])->name('grupos.store');
        Route::put('/grupos/{grupo}', [GrupoController::class, 'update'])->name('grupos.update');
        Route::delete('/grupos/{grupo}', [GrupoController::class, 'destroy'])->name('grupos.destroy');

        // Asignaturas
        Route::post('/asignaturas', [AsignaturaController::class, 'store'])->name('asignaturas.store');
        Route::put('/asignaturas/{asignatura}', [AsignaturaController::class, 'update'])->name('asignaturas.update');
        Route::delete('/asignaturas/{asignatura}', [AsignaturaController::class, 'destroy'])->name('asignaturas.destroy');

        // Alumnos
        Route::post('/alumnos', [AlumnoController::class, 'store'])->name('alumnos.store');
        Route::put('/alumnos/{alumno}', [AlumnoController::class, 'update'])->name('alumnos.update');
        Route::delete('/alumnos/{alumno}', [AlumnoController::class, 'destroy'])->name('alumnos.destroy');

        // Calificaciones
        Route::post('/calificaciones', [CalificacionController::class, 'store'])->name('calificaciones.store');
        Route::put('/calificaciones/{calificacione}', [CalificacionController::class, 'update'])->name('calificaciones.update');
        Route::delete('/calificaciones/{calificacione}', [CalificacionController::class, 'destroy'])->name('calificaciones.destroy');

        // Profesores
        Route::post('/profesores', [ProfesorController::class, 'store'])->name('profesores.store');
        Route::put('/profesores/{profesor}', [ProfesorController::class, 'update'])->name('profesores.update');
        Route::delete('/profesores/{profesor}', [ProfesorController::class, 'destroy'])->name('profesores.destroy');

        // Horarios
        Route::post('/horarios', [HorarioAsignaturaController::class, 'store'])->name('horarios.store');
        Route::delete('/horarios/{horario}', [HorarioAsignaturaController::class, 'destroy'])->name('horarios.destroy');

        // Asignaciones
        Route::post('/asignaciones/alumnos', [AsignacionController::class, 'asignarAlumno'])->name('asignaciones.asignarAlumno');
        Route::delete('/asignaciones/alumnos/{asignacion}', [AsignacionController::class, 'desasignarAlumno'])->name('asignaciones.desasignarAlumno');
        
        Route::post('/asignaciones/profesores', [AsignacionController::class, 'asignarProfesor'])->name('asignaciones.asignarProfesor');
        Route::delete('/asignaciones/profesores/{asignacion}', [AsignacionController::class, 'desasignarProfesor'])->name('asignaciones.desasignarProfesor');

        // Usuarios (Alta de usuarios)
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
        Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
        Route::put('/usuarios/{usuario}', [UserController::class, 'update'])->name('usuarios.update');
        Route::delete('/usuarios/{usuario}', [UserController::class, 'destroy'])->name('usuarios.destroy');

        // Bitácora
        Route::get('/bitacora', [\App\Http\Controllers\BitacoraController::class, 'index'])->name('bitacora.index');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
