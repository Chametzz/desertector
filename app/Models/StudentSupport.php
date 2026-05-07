<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSupport extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'tutor_id', 'action_taken', 'description', 'date'];

    protected $casts = ['date' => 'date'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
}
