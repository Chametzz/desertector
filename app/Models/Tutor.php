<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    use HasFactory;

    protected $fillable = ['person_id', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'tutor_id');
    }

    public function reportedIncidents()
    {
        return $this->hasMany(StudentIncident::class, 'tutor_id');
    }

    public function writtenObservations()
    {
        return $this->hasMany(StudentObservation::class, 'tutor_id');
    }

    public function supports()
    {
        return $this->hasMany(StudentSupport::class);
    }
}
