<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'people';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'second_last_name',
        'birth_date',
        'gender'
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function personProfiles()
    {
        return $this->hasMany(PersonProfile::class, 'person_id');
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function tutor()
    {
        return $this->hasOne(Tutor::class);
    }

    // Encuestas que ha creado
    public function createdSurveys()
    {
        return $this->hasMany(Survey::class, 'created_by');
    }

    // Respuestas a encuestas
    public function surveyResponses()
    {
        return $this->hasMany(SurveyResponse::class);
    }

    // Asignaciones de encuestas (relación muchos a muchos)
    public function surveyAssignments()
    {
        return $this->belongsToMany(SurveyAssignment::class, 'survey_assignment_people', 'person_id', 'survey_assignment_id');
    }

    //Nombre completo
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name} {$this->second_last_name}");
    }
}
