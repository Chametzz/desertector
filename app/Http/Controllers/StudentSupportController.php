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
        $user = Auth::user();
        $query = StudentSupport::with(['student.person', 'tutor.person'])->latest('date');

        if ($user->isTutor()) {
            // Tutor: solo apoyos de los estudiantes que tiene asignados
            $tutor = $user->person?->tutor;
            if ($tutor) {
                // Usamos whereHas para filtrar por el tutor del estudiante
                $query->whereHas('student', function ($q) use ($tutor) {
                    $q->where('tutor_id', $tutor->id);
                });
            } else {
                // Si no tiene perfil de tutor, no mostrar nada
                $query->whereRaw('0');
            }
        }
        // Si el usuario es administrador u otro rol, se pueden ver todos los apoyos (sin filtro)
        // Opcional: si se desea que solo tutores accedan, se puede agregar un abort(403) para otros roles.

        $supports = $query->paginate(15);
        return view('student_supports.index', compact('supports'));
    }

    public function create()
    {
        // Solo tutores pueden crear apoyos (ya verificado en el middleware o en políticas)
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
        $tutor = Auth::user()->person?->tutor;
        if (!$tutor || $student_support->tutor_id !== $tutor->id) {
            abort(403, 'No tienes permiso para editar este apoyo.');
        }

        $students = Student::with('person')->get();
        $tutors = Tutor::with('person')->get();
        return view('student_supports.edit', compact('student_support', 'students', 'tutors'));
    }

    public function update(Request $request, StudentSupport $student_support)
    {
        $tutor = Auth::user()->person?->tutor;
        if (!$tutor || $student_support->tutor_id !== $tutor->id) {
            return redirect()->back()->with('error', 'No tienes permiso para editar este apoyo.');
        }

        $validated = $request->validate([
            'student_id'    => 'required|exists:students,id',
            'action_taken'  => 'required|string|max:100',
            'description'   => 'required|string|min:5',
            'date'          => 'required|date',
        ]);

        $student_support->update($validated);

        return redirect()->route('student_supports.index')
            ->with('success', 'Apoyo actualizado correctamente.');
    }

    public function destroy(StudentSupport $student_support)
    {
        $tutor = Auth::user()->person?->tutor;
        if (!$tutor || $student_support->tutor_id !== $tutor->id) {
            return redirect()->back()->with('error', 'No tienes permiso para eliminar este apoyo.');
        }

        $student_support->delete();
        return redirect()->route('student_supports.index')
            ->with('success', 'Apoyo eliminado.');
    }
}
