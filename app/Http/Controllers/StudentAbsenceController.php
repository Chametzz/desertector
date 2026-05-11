<?php

namespace App\Http\Controllers;

use App\Models\StudentAbsence;
use App\Models\Subject;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class StudentAbsenceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $subjects = collect();
        $students = collect();

        /*if ($user->isTeacher()) {
            // Profesor: materias que imparte (puedes obtener de relación teacher->subjects)
            // $subjects = Subject::whereHas('teachers', fn($q) => $q->where('teacher_id', $user->teacher->id))->get();
            // Si no hay relación directa, podrías usar todas las materias donde ha reportado ausencias
            $subjects = Subject::whereIn('id', StudentAbsence::where('teacher_id', $user->teacher->id)->pluck('subject_id'))->get();
        } elseif ($user->isTutor()) {
            // Tutor: obtiene sus estudiantes asignados
            $students = Student::where('tutor_id', $user->tutor->id)->get();
        }*/

        return view('student_absences.index', compact('subjects', 'students'));
    }

    public function getEvents(Request $request)
    {
        $user = Auth::user();
        $query = StudentAbsence::with(['student.person', 'subject']);

        if ($user->isTeacher()) {
            $query->where('teacher_id', $user->teacher->id);
            if ($request->subject_id) {
                $query->where('subject_id', $request->subject_id);
            }
        } elseif ($user->isTutor()) {
            $studentIds = Student::where('tutor_id', $user->tutor->id)->pluck('id');
            $query->whereIn('student_id', $studentIds);
            if ($request->student_id) {
                $query->where('student_id', $request->student_id);
            }
        }

        $absences = $query->get();

        return response()->json($absences->map(function ($absence) {
            return [
                'id' => $absence->id,
                'title' => $absence->student->person->full_name . ' - ' . $absence->subject->name,
                'start' => $absence->date,
                'extendedProps' => [
                    'justified' => $absence->is_justified,
                    'reason' => $absence->justification_reason,
                    'student_id' => $absence->student_id,
                    'subject_id' => $absence->subject_id,
                ],
                'color' => $absence->is_justified ? '#10b981' : '#ef4444',
            ];
        }));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', StudentAbsence::class); // solo profesor

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'is_justified' => 'boolean',
            'justification_reason' => 'nullable|string|max:500',
        ]);

        $validated['teacher_id'] = Auth::user()->teacher->id;
        $validated['is_justified'] = $request->boolean('is_justified');

        StudentAbsence::create($validated);

        return redirect()->route('student_absences.index')->with('success', 'Ausencia registrada.');
    }

    public function update(Request $request, StudentAbsence $absence)
    {
        Gate::authorize('update', $absence);

        $validated = $request->validate([
            'is_justified' => 'boolean',
            'justification_reason' => 'nullable|string|max:500',
            'date' => 'sometimes|date',
        ]);

        $absence->update($validated);

        return redirect()->route('student_absences.index')->with('success', 'Ausencia actualizada.');
    }

    public function destroy(StudentAbsence $absence)
    {
        Gate::authorize('delete', $absence);
        $absence->delete();
        return redirect()->route('student_absences.index')->with('success', 'Ausencia eliminada.');
    }

    // API para buscar alumnos (autocompletado para profesor)
    public function searchStudents(Request $request)
    {
        $search = $request->get('q');
        $students = Student::with('person')
            ->whereHas('person', function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('control_number', 'like', "%$search%");
            })
            ->limit(10)
            ->get();

        return response()->json($students->map(fn($s) => [
            'id' => $s->id,
            'text' => $s->person->full_name . ' (' . $s->control_number . ')',
        ]));
    }
}
