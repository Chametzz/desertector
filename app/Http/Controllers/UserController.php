<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Person;
use App\Models\PersonProfile;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Tutor;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Muestra la lista de usuarios con su información personal y roles.
     */
    public function index()
    {
        $users = User::with('person.personProfiles')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     */
    public function create()
    {
        $majors = Major::orderBy('name')->get();
        $tutors = Tutor::with('person')->get();
        return view('users.create', compact('majors', 'tutors'));
    }

    /**
     * Almacena un nuevo usuario, su persona y sus perfiles en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Datos de User
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|string|email|max:255|unique:users',
            'password'              => 'required|string|min:8|confirmed',
            // Datos de Person
            'first_name'            => 'required|string|max:50',
            'last_name'             => 'required|string|max:50',
            'second_last_name'      => 'nullable|string|max:50',
            'birth_date'            => 'required|date|before:today',
            'gender'                => 'required|in:m,f,o',
            // Roles (múltiples)
            'roles'                 => 'required|array|min:1',
            'roles.*'               => 'in:admin,student,teacher,tutor',
            // Datos específicos para Student
            'control_number'        => 'required_if:roles.*,student|nullable|string|max:20|unique:students,control_number',
            'major_id'              => 'required_if:roles.*,student|nullable|exists:majors,id',
            'tutor_id'              => 'nullable|exists:tutors,id',
            'gpa'                   => 'nullable|numeric|min:0|max:100',
            'status'                => 'required_if:roles.*,student|nullable|in:enrolled,on_leave,graduated,dropped_out',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Crear usuario
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // 2. Crear persona
            $person = Person::create([
                'user_id'          => $user->id,
                'first_name'       => $request->first_name,
                'last_name'        => $request->last_name,
                'second_last_name' => $request->second_last_name,
                'birth_date'       => $request->birth_date,
                'gender'           => $request->gender,
            ]);

            // 3. Registrar los roles seleccionados
            foreach ($request->roles as $role) {
                PersonProfile::create([
                    'person_id'    => $person->id,
                    'profile_type' => $role,
                ]);

                // 4. Crear registro específico según el rol
                switch ($role) {
                    case 'student':
                        Student::create([
                            'person_id'      => $person->id,
                            'control_number' => $request->control_number,
                            'major_id'       => $request->major_id,
                            'gpa'            => $request->gpa ?? 0,
                            'tutor_id'       => $request->tutor_id,
                            'status'         => $request->status,
                            'is_active'      => true,
                        ]);
                        break;
                    case 'teacher':
                        Teacher::create([
                            'person_id' => $person->id,
                            'is_active' => true,
                        ]);
                        break;
                    case 'tutor':
                        Tutor::create([
                            'person_id' => $person->id,
                            'is_active' => true,
                        ]);
                        break;
                        // admin no tiene tabla específica
                }
            }
        });

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Muestra los detalles de un usuario específico.
     */
    public function show(User $user)
    {
        $user->load('person.personProfiles', 'person.student', 'person.teacher', 'person.tutor');
        return view('users.show', compact('user'));
    }

    /**
     * Muestra el formulario para editar un usuario existente.
     */
    public function edit(User $user)
    {
        $user->load('person.personProfiles', 'person.student', 'person.teacher', 'person.tutor');
        $majors = Major::orderBy('name')->get();
        $tutors = Tutor::with('person')->get();
        $currentRoles = $user->person->personProfiles->pluck('profile_type')->toArray();
        return view('users.edit', compact('user', 'majors', 'tutors', 'currentRoles'));
    }

    /**
     * Actualiza los datos del usuario, persona y perfiles.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            // Datos de User
            'name'                  => 'required|string|max:255',
            'email'                 => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password'              => 'nullable|string|min:8|confirmed',
            // Datos de Person
            'first_name'            => 'required|string|max:50',
            'last_name'             => 'required|string|max:50',
            'second_last_name'      => 'nullable|string|max:50',
            'birth_date'            => 'required|date|before:today',
            'gender'                => 'required|in:m,f,o',
            // Roles
            'roles'                 => 'required|array|min:1',
            'roles.*'               => 'in:admin,student,teacher,tutor',
            // Datos específicos para Student
            'control_number'        => 'required_if:roles.*,student|nullable|string|max:20|unique:students,control_number,' . optional($user->person->student)->id,
            'major_id'              => 'required_if:roles.*,student|nullable|exists:majors,id',
            'tutor_id'              => 'nullable|exists:tutors,id',
            'gpa'                   => 'nullable|numeric|min:0|max:100',
            'status'                => 'required_if:roles.*,student|nullable|in:enrolled,on_leave,graduated,dropped_out',
        ]);

        DB::transaction(function () use ($request, $user) {
            // 1. Actualizar usuario
            $userData = [
                'name'  => $request->name,
                'email' => $request->email,
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);

            // 2. Actualizar persona
            $person = $user->person;
            $person->update([
                'first_name'       => $request->first_name,
                'last_name'        => $request->last_name,
                'second_last_name' => $request->second_last_name,
                'birth_date'       => $request->birth_date,
                'gender'           => $request->gender,
            ]);

            // 3. Sincronizar roles: eliminar los que ya no están y agregar los nuevos
            $currentRoles = $person->personProfiles->pluck('profile_type')->toArray();
            $newRoles = $request->roles;

            $rolesToRemove = array_diff($currentRoles, $newRoles);
            $rolesToAdd = array_diff($newRoles, $currentRoles);

            foreach ($rolesToRemove as $role) {
                // Eliminar perfil y registro específico
                $person->personProfiles()->where('profile_type', $role)->delete();
                $this->deleteSpecificProfile($person, $role);
            }

            foreach ($rolesToAdd as $role) {
                PersonProfile::create([
                    'person_id'    => $person->id,
                    'profile_type' => $role,
                ]);
                $this->createSpecificProfile($person, $role, $request);
            }

            // Update data for roles that are kept (e.g., student data)
            $rolesToKeep = array_intersect($currentRoles, $newRoles);
            foreach ($rolesToKeep as $role) {
                switch ($role) {
                    case 'student':
                        $student = $person->student;
                        if ($student) {
                            $student->update([
                                'control_number' => $request->control_number,
                                'major_id'       => $request->major_id,
                                'gpa'            => $request->gpa ?? 0,
                                'tutor_id'       => $request->tutor_id,
                                'status'         => $request->status,
                            ]);
                        }
                        break;
                        // Add cases for other roles if they have specific updatable data
                }
            }
        });

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Elimina el usuario y todos sus datos relacionados (en cascada).
     */
    public function destroy(User $user)
    {
        DB::transaction(function () use ($user) {
            // Al eliminar el usuario, por las FK con ON DELETE CASCADE se eliminará:
            // - person
            // - person_profiles
            // - student, teacher, tutor (si existen)
            $user->delete();
        });

        return redirect()->route('users.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }

    // Métodos auxiliares privados

    private function deleteSpecificProfile(Person $person, string $role): void
    {
        switch ($role) {
            case 'student':
                if ($person->student) $person->student->delete();
                break;
            case 'teacher':
                if ($person->teacher) $person->teacher->delete();
                break;
            case 'tutor':
                if ($person->tutor) $person->tutor->delete();
                break;
        }
    }

    private function createSpecificProfile(Person $person, string $role, Request $request): void
    {
        switch ($role) {
            case 'student':
                Student::create([
                    'person_id'      => $person->id,
                    'control_number' => $request->control_number,
                    'major_id'       => $request->major_id,
                    'gpa'            => $request->gpa ?? 0,
                    'tutor_id'       => $request->tutor_id,
                    'status'         => $request->status,
                    'is_active'      => true,
                ]);
                break;
            case 'teacher':
                Teacher::create([
                    'person_id' => $person->id,
                    'is_active' => true,
                ]);
                break;
            case 'tutor':
                Tutor::create([
                    'person_id' => $person->id,
                    'is_active' => true,
                ]);
                break;
        }
    }
}
