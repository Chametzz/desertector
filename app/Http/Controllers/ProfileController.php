<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user()->load([
            'person.personProfiles',
            'person.student.major',
            'person.teacher',
            'person.tutor'
        ]);
        return view('profile.show', compact('user'));
    }
}
