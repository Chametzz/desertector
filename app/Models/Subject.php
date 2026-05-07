<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function absences()
    {
        return $this->hasMany(StudentAbsence::class);
    }

    public function incidents()
    {
        return $this->hasMany(StudentIncident::class);
    }
}
