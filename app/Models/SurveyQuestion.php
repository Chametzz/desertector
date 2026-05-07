<?php
// app/Models/SurveyQuestion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['survey_id', 'title', 'question_type', 'display_order'];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class, 'question_id');
    }

    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class, 'question_id');
    }
}
