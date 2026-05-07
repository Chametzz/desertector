<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SurveyAssignmentPerson extends Pivot
{
    protected $table = 'survey_assignment_people';
    // No hay timestamps en la tabla pivot
    public $timestamps = false;
}
