<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        return response()->json(Categoria::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $categoria = Categoria::create($request->all());
        return response()->json($categoria, 201);
    }

    public function show($id)
    {
        $categoria = Categoria::findOrFail($id);
        return response()->json($categoria);
    }

    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->update($request->all());
        return response()->json($categoria);
    }

    public function destroy($id)
    {
        Categoria::findOrFail($id)->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }
}