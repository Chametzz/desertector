<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAbsence extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teacher_id',
        'subject_id',
        'is_justified',
        'justification_reason',
        'date'
    ];

    protected $casts = [
        'is_justified' => 'boolean',
        'date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
