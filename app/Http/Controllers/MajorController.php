<?php

namespace App\Http\Controllers;

use App\Models\Major;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    public function index()
    {
        $majors = Major::latest()->get();
        return view('majors.index', compact('majors'));
    }

    public function create()
    {
        return view('majors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:majors',
        ]);

        Major::create($validated);

        return redirect()->route('majors.index')->with('success', 'Carrera creada correctamente.');
    }

    public function edit(Major $major)
    {
        return view('majors.edit', compact('major'));
    }

    public function update(Request $request, Major $major)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:majors,name,' . $major->id,
        ]);

        $major->update($validated);

        return redirect()->route('majors.index')->with('success', 'Carrera actualizada correctamente.');
    }

    public function destroy(Major $major)
    {
        $major->delete();
        return redirect()->route('majors.index')->with('success', 'Carrera eliminada correctamente.');
    }
}
