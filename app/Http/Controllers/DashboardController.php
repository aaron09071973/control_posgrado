<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Grupo;
use App\Models\Posgrado;
use App\Models\Semestre;

class DashboardController extends Controller
{
    public function index()
    {
        $kpis = [
            'posgrados' => Posgrado::count(),
            'semestres' => Semestre::count(),
            'grupos' => Grupo::count(),
            'alumnos' => Alumno::count(),
        ];

        return view('dashboard', compact('kpis'));
    }
}
