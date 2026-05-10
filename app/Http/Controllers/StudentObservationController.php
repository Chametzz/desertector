<?php

namespace App\Http\Controllers;

use App\Models\StudentObservation;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentObservationController extends Controller
{
    public function index()
    {
        $observations = StudentObservation::with(['student.person', 'reporter'])
            ->latest('created_at')
            ->paginate(15);
        return view('student_observations.index', compact('observations'));
    }

    public function create()
    {
        $students = Student::with('person')->get();
        return view('student_observations.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'   => 'required|exists:students,id',
            'description'  => 'required|string|min:5',
        ]);

        $person = Auth::user()->person;
        if (!$person) {
            return redirect()->back()->with('error', 'No se encontró tu perfil de persona.');
        }

        $validated['reporter_id'] = $person->id;
        StudentObservation::create($validated);

        return redirect()->route('student_observations.index')
            ->with('success', 'Observación registrada correctamente.');
    }

    public function edit(StudentObservation $student_observation)
    {
        $students = Student::with('person')->get();
        return view('student_observations.edit', compact('student_observation', 'students'));
    }

    public function update(Request $request, StudentObservation $student_observation)
    {
        $validated = $request->validate([
            'student_id'   => 'required|exists:students,id',
            'description'  => 'required|string|min:5',
        ]);

        $student_observation->update($validated);

        return redirect()->route('student_observations.index')
            ->with('success', 'Observación actualizada correctamente.');
    }

    public function destroy(StudentObservation $student_observation)
    {
        $student_observation->delete();
        return redirect()->route('student_observations.index')
            ->with('success', 'Observación eliminada.');
    }
}
