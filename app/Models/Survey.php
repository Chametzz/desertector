<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'created_by', 'is_public'];

    protected $casts = ['is_public' => 'boolean'];

    public function creator()
    {
        return $this->belongsTo(Person::class, 'created_by');
    }

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class);
    }

    public function assignments()
    {
        return $this->hasMany(SurveyAssignment::class);
    }
}
