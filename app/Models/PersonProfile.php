<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonProfile extends Model
{
    use HasFactory;

    protected $fillable = ['person_id', 'profile_type'];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
