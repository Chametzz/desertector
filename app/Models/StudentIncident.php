<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentIncident extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teacher_id',
        'tutor_id',
        'subject_id',
        'category_id',
        'risk_level',
        'description',
        'date'
    ];

    protected $casts = [
        'risk_level' => 'integer',
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

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function category()
    {
        return $this->belongsTo(IncidentCategory::class, 'category_id');
    }
}
