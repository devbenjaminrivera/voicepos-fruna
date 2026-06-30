<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Producto::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nombre', 'like', "%{$search}%");
        }

        $productos = $query->orderBy('nombre', 'asc')->paginate(10);
        return view('productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('productos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio_unitario' => 'required|integer|min:0',
        ]);

        try {
            Producto::create($request->all());
            return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error creando producto: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error al guardar el producto.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        // No es estrictamente necesario en este CRUD administrativo
        return redirect()->route('productos.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio_unitario' => 'required|integer|min:0',
        ]);

        try {
            $producto->update($request->all());
            return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error actualizando producto: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error al actualizar el producto.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        try {
            // Verificar si el producto está en boletas (evitar foreign key constraints error)
            if($producto->boletas()->count() > 0) {
                return redirect()->route('productos.index')->with('error', 'No se puede eliminar el producto porque está asociado a boletas existentes.');
            }
            
            $producto->delete();
            return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error eliminando producto: ' . $e->getMessage());
            return redirect()->route('productos.index')->with('error', 'Error al eliminar el producto.');
        }
    }
}
