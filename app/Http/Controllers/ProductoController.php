<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with('categoria')->get();
        return response()->json($productos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'nombre'       => 'required|string|max:100',
            'talla'        => 'required|string|max:10',
            'precio'       => 'required|numeric|min:0',
            'imagen'       => 'nullable|image|max:2048',
        ]);

        $datos = $request->except('imagen');

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto = Producto::create($datos);
        return response()->json($producto->load('categoria'), 201);
    }

    public function show($id)
    {
        $producto = Producto::with('categoria')->findOrFail($id);
        return response()->json($producto);
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $datos = $request->except('imagen');

        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $datos['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($datos);
        return response()->json($producto->load('categoria'));
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);

        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }
        $producto->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }
}