<?php

namespace App\Livewire;

use App\Models\Student;
use App\Models\StudentAbsence;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StudentAttendanceCalendar extends Component
{
    public $student_id;
    public $subject_id;
    public $date;

    // Modo de visualización: 'teacher' o 'tutor'
    public $mode = 'teacher';

    // Modal de justificación (solo modo docente)
    public $showJustifyModal = false;
    public $justifyAbsenceId = null;
    public $justifyIsJustified = false;
    public $justifyReason = '';

    public function mount()
    {
        $this->date = Carbon::now()->startOfMonth();
        $user = Auth::user();

        // Determinar modo inicial según los roles disponibles
        if ($user->isTeacher() && $user->isTutor()) {
            // Si tiene ambos, por defecto modo docente (puede cambiar)
            $this->mode = 'teacher';
        } elseif ($user->isTeacher()) {
            $this->mode = 'teacher';
        } elseif ($user->isTutor()) {
            $this->mode = 'tutor';
        } else {
            $this->mode = 'teacher'; // fallback
        }
    }

    // Cambiar entre modo docente y tutor (solo si tiene ambos roles)
    public function switchMode($mode)
    {
        $user = Auth::user();
        if ($mode === 'teacher' && !$user->isTeacher()) return;
        if ($mode === 'tutor' && !$user->isTutor()) return;

        $this->mode = $mode;
        // Al cambiar de modo, reiniciamos los filtros para evitar inconsistencias
        $this->student_id = null;
        $this->subject_id = null;
        $this->resetErrorBag();
    }

    public function changeMonth($offset)
    {
        $this->date = Carbon::parse($this->date)->addMonths($offset)->startOfMonth();
    }

    // Solo para modo docente: crear o eliminar ausencia sin justificación (toggle rápido)
    public function toggleAbsence($day)
    {
        $user = Auth::user();
        if ($this->mode !== 'teacher' || !$user->isTeacher()) {
            session()->flash('error', 'No tienes permiso para modificar ausencias en este modo.');
            return;
        }

        if (!$this->student_id || !$this->subject_id) return;

        $targetDate = Carbon::parse($this->date)->day($day)->format('Y-m-d');
        $teacherId = $user->person?->teacher?->id;

        if (!$teacherId) {
            session()->flash('error', 'No tienes perfil de docente.');
            return;
        }

        $absence = StudentAbsence::where([
            'student_id' => $this->student_id,
            'subject_id' => $this->subject_id,
            'date' => $targetDate,
        ])->first();

        if ($absence && $absence->teacher_id == $teacherId) {
            $absence->delete();
            session()->flash('message', 'Ausencia eliminada.');
        } elseif (!$absence) {
            StudentAbsence::create([
                'student_id' => $this->student_id,
                'teacher_id' => $teacherId,
                'subject_id' => $this->subject_id,
                'date' => $targetDate,
                'is_justified' => false,
                'justification_reason' => null,
            ]);
            session()->flash('message', 'Ausencia registrada.');
        } else {
            session()->flash('error', 'No puedes modificar una ausencia que no registraste.');
        }
    }

    // Abrir modal de justificación (solo modo docente)
    public function openJustifyModal($absenceId)
    {
        $user = Auth::user();
        if ($this->mode !== 'teacher' || !$user->isTeacher()) {
            session()->flash('error', 'No tienes permiso.');
            return;
        }

        $absence = StudentAbsence::find($absenceId);
        if (!$absence) return;

        $teacherId = $user->person?->teacher?->id;
        if ($absence->teacher_id != $teacherId) {
            session()->flash('error', 'Solo puedes justificar tus propias ausencias.');
            return;
        }

        $this->justifyAbsenceId = $absence->id;
        $this->justifyIsJustified = $absence->is_justified;
        $this->justifyReason = $absence->justification_reason ?? '';
        $this->showJustifyModal = true;
    }

    public function saveJustification()
    {
        $user = Auth::user();
        if ($this->mode !== 'teacher' || !$user->isTeacher()) {
            session()->flash('error', 'No tienes permiso.');
            $this->showJustifyModal = false;
            return;
        }

        if (!$this->justifyAbsenceId) return;

        $absence = StudentAbsence::find($this->justifyAbsenceId);
        $teacherId = $user->person?->teacher?->id;

        if ($absence && $absence->teacher_id == $teacherId) {
            $absence->update([
                'is_justified' => $this->justifyIsJustified,
                'justification_reason' => $this->justifyReason ?: null,
            ]);
            session()->flash('message', 'Justificación actualizada.');
        } else {
            session()->flash('error', 'No tienes permiso.');
        }

        $this->showJustifyModal = false;
        $this->reset(['justifyAbsenceId', 'justifyIsJustified', 'justifyReason']);
    }

    public function deleteFromModal()
    {
        $user = Auth::user();
        if ($this->mode !== 'teacher' || !$user->isTeacher()) {
            session()->flash('error', 'No tienes permiso.');
            $this->showJustifyModal = false;
            return;
        }

        if (!$this->justifyAbsenceId) return;

        $absence = StudentAbsence::find($this->justifyAbsenceId);
        $teacherId = $user->person?->teacher?->id;

        if ($absence && $absence->teacher_id == $teacherId) {
            $absence->delete();
            session()->flash('message', 'Ausencia eliminada.');
        } else {
            session()->flash('error', 'No tienes permiso.');
        }

        $this->showJustifyModal = false;
        $this->reset(['justifyAbsenceId', 'justifyIsJustified', 'justifyReason']);
    }

    public function render()
    {
        $user = Auth::user();
        $carbonDate = Carbon::parse($this->date);
        $daysInMonth = $carbonDate->daysInMonth;
        $firstDayOfMonth = $carbonDate->copy()->startOfMonth()->dayOfWeekIso;

        // Obtener el ID del docente si es modo docente y el usuario es teacher
        $teacherId = null;
        if ($this->mode === 'teacher' && $user->isTeacher()) {
            $teacherId = $user->person?->teacher?->id;
        }

        // 1. Obtener estudiantes según modo
        if ($this->mode === 'teacher' && $user->isTeacher()) {
            $students = Student::with('person')->get();
        } elseif ($this->mode === 'tutor' && $user->isTutor()) {
            $tutor = $user->person?->tutor;
            $students = $tutor ? Student::with('person')->where('tutor_id', $tutor->id)->get() : collect();
        } else {
            $students = collect();
        }

        // Limpiar selección si el estudiante no está disponible
        if ($this->student_id && !$students->contains('id', $this->student_id)) {
            $this->student_id = null;
        }

        // 2. Obtener ausencias con filtro de docente en modo teacher
        $absences = collect();
        if ($this->student_id && $this->subject_id) {
            $query = StudentAbsence::where('student_id', $this->student_id)
                ->where('subject_id', $this->subject_id)
                ->whereMonth('date', $carbonDate->month)
                ->whereYear('date', $carbonDate->year);

            if ($teacherId) {
                $query->where('teacher_id', $teacherId);
            }

            $absences = $query->get()->keyBy(fn($a) => Carbon::parse($a->date)->day);
        }

        $subjects = Subject::all();

        return view('livewire.student-attendance-calendar', [
            'students' => $students,
            'subjects' => $subjects,
            'daysInMonth' => $daysInMonth,
            'firstDayOfMonth' => $firstDayOfMonth,
            'absences' => $absences,
            'monthName' => $carbonDate->translatedFormat('F Y'),
            'isTeacher' => $user->isTeacher(),
            'isTutor' => $user->isTutor(),
            'currentMode' => $this->mode,
            'canSwitch' => $user->isTeacher() && $user->isTutor(),
        ]);
    }
}
