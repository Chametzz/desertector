<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyAssignment extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'survey_id', 'max_responses', 'due_date'];

    protected $casts = [
        'due_date' => 'datetime',
        'max_responses' => 'integer',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function people()
    {
        return $this->belongsToMany(Person::class, 'survey_assignment_people', 'survey_assignment_id', 'person_id');
    }

    public function responses()
    {
        return $this->hasMany(SurveyResponse::class, 'survey_assignments_id');
    }
}
