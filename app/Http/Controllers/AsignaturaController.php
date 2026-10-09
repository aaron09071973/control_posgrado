<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use Illuminate\Http\Request;

class AsignaturaController extends Controller
{
    public function index()
    {
        $asignaturas = Asignatura::all();
        return view('asignaturas.index', compact('asignaturas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_asignatura' => 'required|string|max:150',
            'codigo_asignatura' => 'required|string|max:20|unique:Asignaturas,codigo_asignatura',
            'Horario' => 'required|string|max:100',
            'hrslectivas' => 'required|integer|min:1',
            'tipo_asignatura' => 'nullable|string|in:Optativa,Obligatoria',
        ]);

        Asignatura::create($validated);
        return redirect()->route('asignaturas.index')->with('success', 'Asignatura creada exitosamente.');
    }

    public function update(Request $request, Asignatura $asignatura)
    {
        $validated = $request->validate([
            'nombre_asignatura' => 'required|string|max:150',
            'codigo_asignatura' => 'required|string|max:20|unique:Asignaturas,codigo_asignatura,' . $asignatura->id_asignatura . ',id_asignatura',
            'Horario' => 'required|string|max:100',
            'hrslectivas' => 'required|integer|min:1',
            'tipo_asignatura' => 'nullable|string|in:Optativa,Obligatoria',
        ]);

        $asignatura->update($validated);
        return redirect()->route('asignaturas.index')->with('success', 'Asignatura actualizada exitosamente.');
    }

    public function destroy(Asignatura $asignatura)
    {
        $asignatura->delete();
        return redirect()->route('asignaturas.index')->with('success', 'Asignatura eliminada exitosamente.');
    }
}
