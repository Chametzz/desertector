<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    use HasFactory;

    protected $fillable = ['survey_assignments_id', 'person_id', 'attempt_number', 'completed_at'];

    protected $casts = [
        'completed_at' => 'datetime',
        'attempt_number' => 'integer',
    ];

    public function assignment()
    {
        return $this->belongsTo(SurveyAssignment::class, 'survey_assignments_id');
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class, 'response_id');
    }
}
