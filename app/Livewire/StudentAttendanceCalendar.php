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

    // Modal de justificación
    public $showJustifyModal = false;
    public $justifyAbsenceId = null;
    public $justifyIsJustified = false;
    public $justifyReason = '';

    public function mount()
    {
        $this->date = Carbon::now()->startOfMonth();
    }

    public function changeMonth($offset)
    {
        $this->date = Carbon::parse($this->date)->addMonths($offset)->startOfMonth();
    }

    // Toggle rápido (crea o elimina ausencia sin justificación)
    public function toggleAbsence($day)
    {
        if (!$this->student_id || !$this->subject_id) return;

        $targetDate = Carbon::parse($this->date)->day($day)->format('Y-m-d');

        $teacherId = Auth::user()->person?->teacher?->id;
        if (!$teacherId) {
            session()->flash('error', 'No tienes perfil de docente.');
            return;
        }

        $absence = StudentAbsence::where([
            'student_id' => $this->student_id,
            'subject_id' => $this->subject_id,
            'date' => $targetDate,
        ])->first();

        if ($absence) {
            $absence->delete();
            session()->flash('message', 'Ausencia eliminada.');
        } else {
            StudentAbsence::create([
                'student_id' => $this->student_id,
                'teacher_id' => $teacherId,
                'subject_id' => $this->subject_id,
                'date' => $targetDate,
                'is_justified' => false,
                'justification_reason' => null,
            ]);
            session()->flash('message', 'Ausencia registrada.');
        }
    }

    // Abrir modal de justificación para una ausencia existente
    public function openJustifyModal($absenceId)
    {
        $absence = StudentAbsence::find($absenceId);
        if (!$absence) return;

        $this->justifyAbsenceId = $absence->id;
        $this->justifyIsJustified = $absence->is_justified;
        $this->justifyReason = $absence->justification_reason ?? '';
        $this->showJustifyModal = true;
    }

    // Guardar justificación
    public function saveJustification()
    {
        if (!$this->justifyAbsenceId) return;

        $absence = StudentAbsence::find($this->justifyAbsenceId);
        $teacherId = Auth::user()->person?->teacher?->id;
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

    // Eliminar desde el modal
    public function deleteFromModal()
    {
        if (!$this->justifyAbsenceId) return;
        $absence = StudentAbsence::find($this->justifyAbsenceId);
        $teacherId = Auth::user()->person?->teacher?->id;
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
        $carbonDate = Carbon::parse($this->date);
        $daysInMonth = $carbonDate->daysInMonth;
        $firstDayOfMonth = $carbonDate->copy()->startOfMonth()->dayOfWeekIso;

        $absences = collect();
        if ($this->student_id && $this->subject_id) {
            $absences = StudentAbsence::where('student_id', $this->student_id)
                ->where('subject_id', $this->subject_id)
                ->whereMonth('date', $carbonDate->month)
                ->whereYear('date', $carbonDate->year)
                ->get()
                ->keyBy(fn($a) => Carbon::parse($a->date)->day);
        }

        $students = Student::with('person')->get();
        $subjects = Subject::all();

        return view('livewire.student-attendance-calendar', [
            'students' => $students,
            'subjects' => $subjects,
            'daysInMonth' => $daysInMonth,
            'firstDayOfMonth' => $firstDayOfMonth,
            'absences' => $absences,
            'monthName' => $carbonDate->translatedFormat('F Y'),
        ]);
    }
}
