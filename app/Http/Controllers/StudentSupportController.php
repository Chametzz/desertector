<?php

namespace App\Http\Controllers;

use App\Models\StudentSupport;
use App\Models\Student;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentSupportController extends Controller
{
    public function index()
    {
        $supports = StudentSupport::with(['student.person', 'tutor.person'])
            ->latest('date')
            ->paginate(15);
        return view('student_supports.index', compact('supports'));
    }

    public function create()
    {
        $students = Student::with('person')->get();
        $tutors = Tutor::with('person')->get();
        return view('student_supports.create', compact('students', 'tutors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'    => 'required|exists:students,id',
            'action_taken'  => 'required|string|max:100',
            'description'   => 'required|string|min:5',
            'date'          => 'required|date',
        ]);

        // Asignar el tutor autenticado (si el usuario tiene perfil de tutor)
        $tutor = Auth::user()->person?->tutor;
        if (!$tutor) {
            return redirect()->back()->with('error', 'No tienes un perfil de tutor asignado.');
        }

        $validated['tutor_id'] = $tutor->id;
        StudentSupport::create($validated);

        return redirect()->route('student_supports.index')
            ->with('success', 'Apoyo registrado correctamente.');
    }

    public function edit(StudentSupport $student_support)
    {
        $students = Student::with('person')->get();
        $tutors = Tutor::with('person')->get();
        return view('student_supports.edit', compact('student_support', 'students', 'tutors'));
    }

    public function update(Request $request, StudentSupport $student_support)
    {
        $validated = $request->validate([
            'student_id'    => 'required|exists:students,id',
            'action_taken'  => 'required|string|max:100',
            'description'   => 'required|string|min:5',
            'date'          => 'required|date',
        ]);

        // Opcional: verificar que el tutor autenticado sea el mismo que creó el apoyo
        $tutor = Auth::user()->person?->tutor;
        if ($tutor && $student_support->tutor_id !== $tutor->id) {
            return redirect()->back()->with('error', 'No tienes permiso para editar este apoyo.');
        }

        $student_support->update($validated);

        return redirect()->route('student_supports.index')
            ->with('success', 'Apoyo actualizado correctamente.');
    }

    public function destroy(StudentSupport $student_support)
    {
        $tutor = Auth::user()->person?->tutor;
        if ($tutor && $student_support->tutor_id !== $tutor->id) {
            return redirect()->back()->with('error', 'No tienes permiso para eliminar este apoyo.');
        }

        $student_support->delete();
        return redirect()->route('student_supports.index')
            ->with('success', 'Apoyo eliminado.');
    }
}
