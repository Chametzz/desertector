<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    // Relación con User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con PersonProfile (perfiles)
    public function personProfiles(): HasMany
    {
        return $this->hasMany(PersonProfile::class, 'person_id');
    }

    // Alias más corto para perfiles
    public function profiles(): HasMany
    {
        return $this->hasMany(PersonProfile::class, 'person_id');
    }

    // Relaciones con perfiles específicos
    public function student(): HasOne
    {
        return $this->hasOne(Student::class, 'person_id');
    }

    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class, 'person_id');
    }

    public function tutor(): HasOne
    {
        return $this->hasOne(Tutor::class, 'person_id');
    }

    // Encuestas que ha creado
    public function createdSurveys(): HasMany
    {
        return $this->hasMany(Survey::class, 'created_by');
    }

    // Respuestas a encuestas
    public function surveyResponses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class, 'person_id');
    }

    // Asignaciones de encuestas (relación muchos a muchos)
    public function surveyAssignments()
    {
        return $this->belongsToMany(
            SurveyAssignment::class,
            'survey_assignment_people',
            'person_id',
            'survey_assignment_id'
        );
    }

    // Nombre completo
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name} " . ($this->second_last_name ?? ''));
    }
}
