<?php

namespace App\Http\Controllers;

use App\Models\IncidentCategory;
use Illuminate\Http\Request;

class IncidentCategoryController extends Controller
{
    /**
     * Muestra el listado de categorías.
     */
    public function index()
    {
        $incident_categories = IncidentCategory::latest()->paginate(10);
        return view('incident_categories.index', compact('incident_categories'));
    }

    /**
     * Muestra el formulario para crear una nueva categoría.
     */
    public function create()
    {
        return view('incident_categories.create');
    }

    /**
     * Almacena una nueva categoría en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:50|unique:incident_categories',
            'description' => 'required|string|max:255',
        ]);

        IncidentCategory::create($validated);

        return redirect()->route('incident_categories.index')
            ->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Muestra el formulario para editar una categoría existente.
     */
    public function edit(IncidentCategory $incident_category)
    {
        return view('incident_categories.edit', compact('incident_category'));
    }

    /**
     * Actualiza una categoría existente.
     */
    public function update(Request $request, IncidentCategory $incident_category)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:50|unique:incident_categories,name,' . $incident_category->id,
            'description' => 'required|string|max:255',
        ]);

        $incident_category->update($validated);

        return redirect()->route('incident_categories.index')
            ->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Elimina una categoría.
     */
    public function destroy(IncidentCategory $incident_category)
    {
        // Verificar si tiene incidentes asociados
        if ($incident_category->incidents()->count() > 0) {
            return redirect()->route('incident_categories.index')
                ->with('error', 'No se puede eliminar la categoría porque tiene incidentes asociados.');
        }

        $incident_category->delete();

        return redirect()->route('incident_categories.index')
            ->with('success', 'Categoría eliminada exitosamente.');
    }
}
