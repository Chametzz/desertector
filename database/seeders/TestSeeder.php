<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Person;
use App\Models\PersonProfile;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Tutor;
use App\Models\Major;
use App\Models\Subject;
use App\Models\StudentAbsence;
use App\Models\IncidentCategory;
use App\Models\StudentIncident;
use App\Models\StudentObservation;
use App\Models\StudentSupport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- 1. Carreras ----------
        $majors = [
            'Ingeniería Informática',
            'Administración de Empresas',
            'Psicología',
            'Medicina',
            'Derecho',
            'Arquitectura',
            'Diseño Gráfico',
            'Contaduría',
        ];
        foreach ($majors as $name) {
            Major::firstOrCreate(['name' => $name]);
        }

        // ---------- 2. Materias ----------
        $subjects = [
            'Programación Web',
            'Bases de Datos',
            'Estructuras de Datos',
            'Cálculo Diferencial',
            'Física General',
            'Química Orgánica',
            'Literatura Universal',
            'Historia del Arte',
            'Derecho Civil',
            'Psicología Educativa',
            'Gestión de Proyectos',
            'Marketing Digital',
        ];
        foreach ($subjects as $name) {
            Subject::firstOrCreate(['name' => $name]);
        }

        // ---------- 3. Categorías de incidentes ----------
        $categories = [
            ['name' => 'Conducta', 'description' => 'Problemas de comportamiento en clase'],
            ['name' => 'Bajo rendimiento', 'description' => 'Calificaciones por debajo del mínimo'],
            ['name' => 'Falta de respeto', 'description' => 'Actitudes irrespetuosas'],
            ['name' => 'Acoso', 'description' => 'Bullying o acoso escolar'],
            ['name' => 'Incumplimiento', 'description' => 'No entrega de tareas o trabajos'],
            ['name' => 'Ausentismo', 'description' => 'Faltas reiteradas sin justificar'],
        ];
        foreach ($categories as $cat) {
            IncidentCategory::firstOrCreate(['name' => $cat['name']], $cat);
        }

        // Contraseña por defecto para todos los usuarios
        $defaultPassword = Hash::make('password');

        // ---------- 4. Admin ----------
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@sistema.com'],
            [
                'name' => 'Admin Sistema',
                'password' => $defaultPassword,
                'email_verified_at' => now(),
            ]
        );
        $adminPerson = Person::firstOrCreate(
            ['user_id' => $adminUser->id],
            [
                'first_name' => 'Admin',
                'last_name' => 'Principal',
                'second_last_name' => null,
                'birth_date' => '1985-01-01',
                'gender' => 'm',
            ]
        );
        PersonProfile::firstOrCreate(
            ['person_id' => $adminPerson->id, 'profile_type' => 'admin']
        );

        // ---------- 5. Profesores (3) ----------
        $teachersData = [
            ['email' => 'juan.perez@instituto.com', 'name' => 'Dr. Juan Pérez', 'first' => 'Juan', 'last' => 'Pérez', 'second' => 'García', 'gender' => 'm'],
            ['email' => 'laura.gomez@instituto.com', 'name' => 'Mtra. Laura Gómez', 'first' => 'Laura', 'last' => 'Gómez', 'second' => 'Martínez', 'gender' => 'f'],
            ['email' => 'carlos.ruiz@instituto.com', 'name' => 'Mtro. Carlos Ruiz', 'first' => 'Carlos', 'last' => 'Ruiz', 'second' => 'López', 'gender' => 'm'],
        ];
        $teachers = [];
        foreach ($teachersData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => $defaultPassword,
                    'email_verified_at' => now(),
                ]
            );
            $person = Person::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $data['first'],
                    'last_name' => $data['last'],
                    'second_last_name' => $data['second'],
                    'birth_date' => '1975-06-' . rand(1, 28),
                    'gender' => $data['gender'],
                ]
            );
            PersonProfile::firstOrCreate(
                ['person_id' => $person->id, 'profile_type' => 'teacher']
            );
            $teacher = Teacher::firstOrCreate(
                ['person_id' => $person->id],
                ['is_active' => true]
            );
            $teachers[] = (object)['person' => $person, 'teacher' => $teacher];
        }

        // ---------- 6. Tutores (2) ----------
        $tutorsData = [
            ['email' => 'patricia.vega@tutores.com', 'name' => 'Lic. Patricia Vega', 'first' => 'Patricia', 'last' => 'Vega', 'second' => 'Ríos', 'gender' => 'f'],
            ['email' => 'roberto.diaz@tutores.com', 'name' => 'Lic. Roberto Díaz', 'first' => 'Roberto', 'last' => 'Díaz', 'second' => 'Soto', 'gender' => 'm'],
        ];
        $tutors = [];
        foreach ($tutorsData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => $defaultPassword,
                    'email_verified_at' => now(),
                ]
            );
            $person = Person::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $data['first'],
                    'last_name' => $data['last'],
                    'second_last_name' => $data['second'],
                    'birth_date' => '1980-03-' . rand(1, 28),
                    'gender' => $data['gender'],
                ]
            );
            PersonProfile::firstOrCreate(
                ['person_id' => $person->id, 'profile_type' => 'tutor']
            );
            $tutor = Tutor::firstOrCreate(
                ['person_id' => $person->id],
                ['is_active' => true]
            );
            $tutors[] = (object)['person' => $person, 'tutor' => $tutor];
        }

        // ---------- 7. Estudiantes (15) ----------
        $studentsList = [];
        $majorIds = Major::pluck('id')->toArray();
        for ($i = 1; $i <= 15; $i++) {
            $gender = rand(0, 1) ? 'm' : 'f';
            $firstName = $gender === 'm' ? 'Alumno' : 'Alumna';
            $email = "estudiante{$i}@instituto.com";
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => "{$firstName} {$i}",
                    'password' => $defaultPassword,
                    'email_verified_at' => now(),
                ]
            );
            $person = Person::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $firstName,
                    'last_name' => 'Apellido' . rand(1, 100),
                    'second_last_name' => 'Segundo' . rand(1, 100),
                    'birth_date' => Carbon::now()->subYears(rand(18, 25))->format('Y-m-d'),
                    'gender' => $gender,
                ]
            );
            PersonProfile::firstOrCreate(
                ['person_id' => $person->id, 'profile_type' => 'student']
            );
            $tutorId = $tutors[array_rand($tutors)]->tutor->id;
            $student = Student::firstOrCreate(
                ['person_id' => $person->id],
                [
                    'control_number' => 'CTRL-' . strtoupper(Str::random(8)),
                    'major_id' => $majorIds[array_rand($majorIds)],
                    'gpa' => rand(60, 98) + (rand(0, 99) / 100),
                    'tutor_id' => $tutorId,
                    'status' => 'enrolled',
                    'is_active' => true,
                ]
            );
            $studentsList[] = (object)['student' => $student, 'person' => $person];
        }

        // ---------- 8. Ausencias (student_absences) ----------
        $subjectIds = Subject::pluck('id')->toArray();
        $startDate = Carbon::now()->subMonths(3);
        $endDate = Carbon::now();

        foreach ($teachers as $teacher) {
            $teacherId = $teacher->teacher->id;
            $numAbsences = rand(20, 40);
            for ($a = 0; $a < $numAbsences; $a++) {
                $student = $studentsList[array_rand($studentsList)]->student;
                $subjectId = $subjectIds[array_rand($subjectIds)];
                $date = Carbon::createFromTimestamp(rand($startDate->timestamp, $endDate->timestamp))->format('Y-m-d');
                $isJustified = rand(0, 1);
                $reason = $isJustified ? ['Enfermedad', 'Permiso familiar', 'Cita médica', 'Problema personal'][rand(0, 3)] : null;
                StudentAbsence::create([
                    'student_id' => $student->id,
                    'teacher_id' => $teacherId,
                    'subject_id' => $subjectId,
                    'is_justified' => $isJustified,
                    'justification_reason' => $reason,
                    'date' => $date,
                ]);
            }
        }

        // ---------- 9. Incidentes (student_incidents) ----------
        $categoryIds = IncidentCategory::pluck('id')->toArray();
        $numIncidents = rand(50, 80);
        for ($i = 0; $i < $numIncidents; $i++) {
            $student = $studentsList[array_rand($studentsList)]->student;
            $reporterType = rand(0, 1) ? 'teacher' : 'tutor';
            if ($reporterType === 'teacher') {
                $reporter = $teachers[array_rand($teachers)]->person;
            } else {
                $reporter = $tutors[array_rand($tutors)]->person;
            }
            $subject = (rand(1, 10) > 3) ? $subjectIds[array_rand($subjectIds)] : null;
            $categoryId = $categoryIds[array_rand($categoryIds)];
            $riskLevel = rand(1, 3);
            $descriptions = [
                'Interrupción constante durante la clase',
                'No entregó el proyecto final a tiempo',
                'Actitud desafiante hacia el docente',
                'Uso indebido del celular en horario de clase',
                'Falta de participación en actividades grupales',
                'Burlas hacia compañeros',
                'Insultos en redes sociales',
                'Incumplimiento de normas del aula',
            ];
            $description = $descriptions[array_rand($descriptions)];
            $incidentDate = Carbon::createFromTimestamp(rand($startDate->timestamp, $endDate->timestamp))->format('Y-m-d');

            StudentIncident::create([
                'student_id' => $student->id,
                'reporter_id' => $reporter->id,
                'subject_id' => $subject,
                'category_id' => $categoryId,
                'risk_level' => $riskLevel,
                'description' => $description,
                'date' => $incidentDate,
            ]);
        }

        // ---------- 10. Observaciones (student_observations) ----------
        $numObs = rand(30, 50);
        for ($i = 0; $i < $numObs; $i++) {
            $student = $studentsList[array_rand($studentsList)]->student;
            $reporterType = rand(0, 1) ? 'teacher' : 'tutor';
            if ($reporterType === 'teacher') {
                $reporter = $teachers[array_rand($teachers)]->person;
            } else {
                $reporter = $tutors[array_rand($tutors)]->person;
            }
            $texts = [
                'Mejora su participación cuando se le motiva individualmente.',
                'Se distrae con facilidad, necesita seguimiento.',
                'Muestra interés en la materia, pero le cuesta organizarse.',
                'Es respetuoso y colaborador.',
                'Presenta dificultades en comprensión lectora.',
                'Tiene buen desempeño en trabajos prácticos.',
            ];
            StudentObservation::create([
                'student_id' => $student->id,
                'reporter_id' => $reporter->id,
                'description' => $texts[array_rand($texts)],
            ]);
        }

        // ---------- 11. Apoyos (student_supports) solo para tutores ----------
        $numSupports = rand(15, 30);
        $actions = [
            'Entrevista psicológica',
            'Sesión de tutoría grupal',
            'Orientación vocacional',
            'Taller de hábitos de estudio',
            'Reunión con padres',
            'Seguimiento académico semanal',
            'Plan de mejora personalizado',
        ];
        for ($i = 0; $i < $numSupports; $i++) {
            $student = $studentsList[array_rand($studentsList)]->student;
            $tutor = $tutors[array_rand($tutors)]->tutor;
            $date = Carbon::createFromTimestamp(rand($startDate->timestamp, $endDate->timestamp))->format('Y-m-d');
            StudentSupport::create([
                'student_id' => $student->id,
                'tutor_id' => $tutor->id,
                'action_taken' => $actions[array_rand($actions)],
                'description' => 'Apoyo realizado según lineamiento institucional. El estudiante mostró disposición.',
                'date' => $date,
            ]);
        }

        // ---------- 12. Usuario de prueba adicional ----------
        User::firstOrCreate(
            ['email' => 'demo@desertector.com'],
            [
                'name' => 'Usuario Demo',
                'password' => $defaultPassword,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('TestSeeder completado. Datos de prueba generados correctamente.');
    }
}
