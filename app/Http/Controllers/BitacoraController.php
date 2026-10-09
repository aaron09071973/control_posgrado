<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BitacoraController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Bitacora::with('user');

        if ($request->filled('id_usuario')) {
            $query->where('id_usuario', $request->id_usuario);
        }

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        $bitacoras = $query->orderBy('created_at', 'desc')->paginate(50)->appends($request->all());
        $usuarios = \App\Models\User::orderBy('name')->get();

        return view('bitacora.index', compact('bitacoras', 'usuarios'));
    }
}
