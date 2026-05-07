<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = ['person_id', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function reportedAbsences()
    {
        return $this->hasMany(StudentAbsence::class, 'teacher_id');
    }

    public function reportedIncidents()
    {
        return $this->hasMany(StudentIncident::class, 'teacher_id');
    }

    public function writtenObservations()
    {
        return $this->hasMany(StudentObservation::class, 'teacher_id');
    }
}
