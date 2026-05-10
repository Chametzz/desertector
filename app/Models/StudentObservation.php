<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentObservation extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'reporter_id', 'description'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function reporter()
    {
        return $this->belongsTo(Person::class);
    }
}
