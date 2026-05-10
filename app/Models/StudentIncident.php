<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentIncident extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'reporter_id',
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

    public function reporter()
    {
        return $this->belongsTo(Person::class);
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
