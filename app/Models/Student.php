<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'control_number',
        'major_id',
        'gpa',
        'tutor_id',
        'status',
        'is_active'
    ];

    protected $casts = [
        'gpa' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function absences()
    {
        return $this->hasMany(StudentAbsence::class);
    }

    public function incidents()
    {
        return $this->hasMany(StudentIncident::class);
    }

    public function observations()
    {
        return $this->hasMany(StudentObservation::class);
    }

    public function supports()
    {
        return $this->hasMany(StudentSupport::class);
    }

    // Asignaciones de encuestas
    public function surveyAssignments()
    {
        return $this->belongsToMany(SurveyAssignment::class, 'survey_assignment_people', 'person_id', 'survey_assignment_id', 'person_id');
    }
}
