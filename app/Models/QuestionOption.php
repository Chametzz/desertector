<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    use HasFactory;

    protected $fillable = ['question_id', 'option_text', 'weight'];

    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class, 'question_id');
    }

    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class, 'option_id');
    }
}
