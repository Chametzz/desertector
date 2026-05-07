<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['response_id', 'question_id', 'option_id', 'number_value', 'text_value'];

    protected $casts = [
        'number_value' => 'decimal:2',
    ];

    public function response()
    {
        return $this->belongsTo(SurveyResponse::class, 'response_id');
    }

    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class, 'question_id');
    }

    public function option()
    {
        return $this->belongsTo(QuestionOption::class, 'option_id');
    }
}
