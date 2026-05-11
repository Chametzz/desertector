<?php

namespace App\Http\Controllers;

use App\Models\StudentIncident;
use App\Models\Student;
use App\Models\Subject;
use App\Models\IncidentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentIncidentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = StudentIncident::with(['student.person', 'reporter', 'subject', 'category'])
            ->latest('date');

        if ($user->isTeacher()) {
            // Docente: solo incidentes que él mismo reportó
            $personId = $user->person?->id;
            if ($personId) {
                $query->where('reporter_id', $personId);
            } else {
                $query->whereRaw('0');
            }
        } elseif ($user->isTutor()) {
            // Tutor: incidentes de los estudiantes que tiene asignados
            $tutor = $user->person?->tutor;
            if ($tutor) {
                $query->whereHas('student', function ($q) use ($tutor) {
                    $q->where('tutor_id', $tutor->id);
                });
            } else {
                $query->whereRaw('0');
            }
        }
        // Si es administrador u otro rol, no se aplica filtro adicional

        $student_incidents = $query->paginate(15);
        return view('student_incidents.index', compact('student_incidents'));
    }

    public function create()
    {
        $students = Student::with('person')->get();
        $subjects = Subject::all();
        $categories = IncidentCategory::all();
        return view('student_incidents.create', compact('students', 'subjects', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'   => 'required|exists:students,id',
            'subject_id'   => 'nullable|exists:subjects,id',
            'category_id'  => 'required|exists:incident_categories,id',
            'risk_level'   => 'required|integer|in:1,2,3',
            'description'  => 'required|string|min:5',
            'date'         => 'required|date',
        ]);

        $person = Auth::user()->person;
        if (!$person) {
            return redirect()->back()->with('error', 'No se encontró tu perfil de persona.');
        }

        $validated['reporter_id'] = $person->id;
        StudentIncident::create($validated);

        return redirect()->route('student_incidents.index')
            ->with('success', 'Incidente registrado correctamente.');
    }

    public function edit(StudentIncident $student_incident)
    {
        // Verificar permisos adicionales si se desea (opcional)
        $students = Student::with('person')->get();
        $subjects = Subject::all();
        $categories = IncidentCategory::all();
        return view('student_incidents.edit', compact('student_incident', 'students', 'subjects', 'categories'));
    }

    public function update(Request $request, StudentIncident $student_incident)
    {
        $validated = $request->validate([
            'student_id'   => 'required|exists:students,id',
            'subject_id'   => 'nullable|exists:subjects,id',
            'category_id'  => 'required|exists:incident_categories,id',
            'risk_level'   => 'required|integer|in:1,2,3',
            'description'  => 'required|string|min:5',
            'date'         => 'required|date',
        ]);

        $student_incident->update($validated);

        return redirect()->route('student_incidents.index')
            ->with('success', 'Incidente actualizado correctamente.');
    }

    public function destroy(StudentIncident $student_incident)
    {
        $student_incident->delete();
        return redirect()->route('student_incidents.index')
            ->with('success', 'Incidente eliminado.');
    }
}
