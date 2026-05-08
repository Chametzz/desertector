<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Person;
use App\Models\PersonProfile;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Tutor;
use App\Models\Major;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear algunas carreras (majors) de ejemplo si no existen
        $majors = [
            'Ingeniería Informática',
            'Administración de Empresas',
            'Psicología',
            'Medicina',
            'Derecho',
        ];

        foreach ($majors as $majorName) {
            Major::firstOrCreate(['name' => $majorName]);
        }

        // --- Usuario Administrador ---
        $adminUser = User::create([
            'name' => 'Admin Principal',
            'email' => 'admin@desertector.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $adminPerson = Person::create([
            'user_id' => $adminUser->id,
            'first_name' => 'Admin',
            'last_name' => 'Principal',
            'second_last_name' => null,
            'birth_date' => '1990-01-01',
            'gender' => 'm',
        ]);

        PersonProfile::create([
            'person_id' => $adminPerson->id,
            'profile_type' => 'admin',
        ]);

        // --- Usuario Docente ---
        $teacherUser = User::create([
            'name' => 'Profesor Juan',
            'email' => 'juan.profesor@desertector.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $teacherPerson = Person::create([
            'user_id' => $teacherUser->id,
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'second_last_name' => 'Gómez',
            'birth_date' => '1985-05-15',
            'gender' => 'm',
        ]);

        PersonProfile::create([
            'person_id' => $teacherPerson->id,
            'profile_type' => 'teacher',
        ]);

        Teacher::create([
            'person_id' => $teacherPerson->id,
            'is_active' => true,
        ]);

        // --- Usuario Tutor ---
        $tutorUser = User::create([
            'name' => 'Tutor María',
            'email' => 'maria.tutor@desertector.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $tutorPerson = Person::create([
            'user_id' => $tutorUser->id,
            'first_name' => 'María',
            'last_name' => 'López',
            'second_last_name' => 'Fernández',
            'birth_date' => '1980-08-22',
            'gender' => 'f',
        ]);

        PersonProfile::create([
            'person_id' => $tutorPerson->id,
            'profile_type' => 'tutor',
        ]);

        $tutor = Tutor::create([
            'person_id' => $tutorPerson->id,
            'is_active' => true,
        ]);

        // --- Usuario Estudiante ---
        $studentUser = User::create([
            'name' => 'Carlos Alumno',
            'email' => 'carlos.alumno@desertector.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $studentPerson = Person::create([
            'user_id' => $studentUser->id,
            'first_name' => 'Carlos',
            'last_name' => 'Ramírez',
            'second_last_name' => 'Salazar',
            'birth_date' => '2000-03-10',
            'gender' => 'm',
        ]);

        PersonProfile::create([
            'person_id' => $studentPerson->id,
            'profile_type' => 'student',
        ]);

        // Obtener una carrera al azar (o fija)
        $major = Major::first();

        Student::create([
            'person_id' => $studentPerson->id,
            'control_number' => 'CTRL-' . strtoupper(Str::random(8)),
            'major_id' => $major ? $major->id : 1,
            'gpa' => 85.50,
            'tutor_id' => $tutor->id,
            'status' => 'enrolled',
            'is_active' => true,
        ]);

        // --- Usuario dual (estudiante + tutor, opcional) ---
        $dualUser = User::create([
            'name' => 'Laura Dual',
            'email' => 'laura.dual@desertector.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $dualPerson = Person::create([
            'user_id' => $dualUser->id,
            'first_name' => 'Laura',
            'last_name' => 'Mendoza',
            'second_last_name' => 'Ríos',
            'birth_date' => '1995-12-01',
            'gender' => 'f',
        ]);

        // Perfiles: estudiante y tutor
        PersonProfile::create([
            'person_id' => $dualPerson->id,
            'profile_type' => 'student',
        ]);
        PersonProfile::create([
            'person_id' => $dualPerson->id,
            'profile_type' => 'tutor',
        ]);

        Student::create([
            'person_id' => $dualPerson->id,
            'control_number' => 'CTRL-' . strtoupper(Str::random(8)),
            'major_id' => $major ? $major->id : 1,
            'gpa' => 92.00,
            'tutor_id' => null,
            'status' => 'enrolled',
            'is_active' => true,
        ]);

        Tutor::create([
            'person_id' => $dualPerson->id,
            'is_active' => true,
        ]);

        // --- Usuario de prueba básico (el que ya existía) ---
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
        ]);

        // Opcional: Llamar a otros seeders si tienes (materias, encuestas, etc.)
        // $this->call([
        //     SubjectSeeder::class,
        //     SurveySeeder::class,
        // ]);
    }
}
