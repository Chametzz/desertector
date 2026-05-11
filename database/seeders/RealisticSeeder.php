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

class RealisticSeeder extends Seeder
{
    public function run()
    {
        // 1. Carreras (TecNM Acapulco)
        $majors = [
            'Ingeniería en Sistemas Computacionales',
            'Ingeniería Industrial',
            'Ingeniería en Gestión Empresarial',
            'Ingeniería Electrónica',
            'Ingeniería Mecánica',
            'Licenciatura en Administración',
            'Contador Público',
            'Arquitectura',
        ];
        foreach ($majors as $name) {
            Major::firstOrCreate(['name' => $name]);
        }

        // 2. Materias típicas (por área)
        $subjects = [
            'Fundamentos de Programación',
            'Programación Orientada a Objetos',
            'Estructura de Datos',
            'Bases de Datos',
            'Redes de Computadoras',
            'Sistemas Operativos',
            'Cálculo Diferencial',
            'Cálculo Integral',
            'Álgebra Lineal',
            'Física General',
            'Química',
            'Estadística',
            'Economía',
            'Contabilidad Básica',
            'Derecho Laboral',
            'Mecánica de Materiales',
            'Termodinámica',
            'Circuitos Eléctricos',
            'Gestión de Proyectos',
            'Desarrollo Sustentable',
            'Inglés Técnico',
        ];
        foreach ($subjects as $name) {
            Subject::firstOrCreate(['name' => $name]);
        }

        // 3. Categorías de incidentes
        $categories = [
            ['name' => 'Conducta', 'description' => 'Problemas de comportamiento en clase'],
            ['name' => 'Bajo rendimiento', 'description' => 'Calificaciones por debajo del promedio'],
            ['name' => 'Falta de respeto', 'description' => 'Actitudes irrespetuosas hacia personal o alumnos'],
            ['name' => 'Acoso', 'description' => 'Bullying o acoso escolar'],
            ['name' => 'Incumplimiento', 'description' => 'No entrega de tareas o trabajos'],
            ['name' => 'Ausentismo', 'description' => 'Faltas reiteradas sin justificar'],
            ['name' => 'Uso indebido de tecnología', 'description' => 'Celular u otros dispositivos en clase'],
        ];
        foreach ($categories as $cat) {
            IncidentCategory::firstOrCreate(['name' => $cat['name']], $cat);
        }

        $defaultPassword = Hash::make('password');

        // Helper para crear usuarios/people/perfiles
        $createPerson = function ($name, $email, $first, $last, $second, $gender, $birthDate, $profileTypes, $extra = null) use ($defaultPassword) {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => $defaultPassword,
                    'email_verified_at' => now(),
                ]
            );
            $person = Person::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $first,
                    'last_name' => $last,
                    'second_last_name' => $second,
                    'birth_date' => $birthDate,
                    'gender' => $gender,
                ]
            );
            foreach ($profileTypes as $type) {
                PersonProfile::firstOrCreate(
                    ['person_id' => $person->id, 'profile_type' => $type]
                );
            }
            // Crear registros específicos según perfil
            if (in_array('teacher', $profileTypes)) {
                Teacher::firstOrCreate(['person_id' => $person->id], ['is_active' => true]);
            }
            if (in_array('tutor', $profileTypes)) {
                Tutor::firstOrCreate(['person_id' => $person->id], ['is_active' => true]);
            }
            if (in_array('student', $profileTypes) && $extra) {
                Student::firstOrCreate(
                    ['person_id' => $person->id],
                    [
                        'control_number' => $extra['control_number'],
                        'major_id' => $extra['major_id'],
                        'gpa' => $extra['gpa'],
                        'tutor_id' => $extra['tutor_id'] ?? null,
                        'status' => $extra['status'] ?? 'enrolled',
                        'is_active' => true,
                    ]
                );
            }
            return $person;
        };

        // --- Poblar carreras IDs ---
        $majorIds = Major::pluck('id', 'name')->toArray();

        // --- Profesores (7) ---
        $teachers = [];
        $teachersData = [
            ['name' => 'Dr. Alberto López García', 'email' => 'alberto.lopez@tecmmx.edu', 'first' => 'Alberto', 'last' => 'López', 'second' => 'García', 'gender' => 'm', 'birth' => '1970-05-12'],
            ['name' => 'Mtra. Carolina Méndez Ríos', 'email' => 'carolina.mendez@tecmmx.edu', 'first' => 'Carolina', 'last' => 'Méndez', 'second' => 'Ríos', 'gender' => 'f', 'birth' => '1982-08-25'],
            ['name' => 'Dr. Javier Torres Vázquez', 'email' => 'javier.torres@tecmmx.edu', 'first' => 'Javier', 'last' => 'Torres', 'second' => 'Vázquez', 'gender' => 'm', 'birth' => '1975-11-03'],
            ['name' => 'Mtra. Lucía Herrera Castro', 'email' => 'lucia.herrera@tecmmx.edu', 'first' => 'Lucía', 'last' => 'Herrera', 'second' => 'Castro', 'gender' => 'f', 'birth' => '1988-02-17'],
            ['name' => 'Mtro. Ricardo Flores Silva', 'email' => 'ricardo.flores@tecmmx.edu', 'first' => 'Ricardo', 'last' => 'Flores', 'second' => 'Silva', 'gender' => 'm', 'birth' => '1979-09-30'],
            ['name' => 'Dra. Sandra Muñoz Ortiz', 'email' => 'sandra.munoz@tecmmx.edu', 'first' => 'Sandra', 'last' => 'Muñoz', 'second' => 'Ortiz', 'gender' => 'f', 'birth' => '1985-07-22'],
            ['name' => 'Mtra. Verónica Rangel Pineda', 'email' => 'veronica.rangel@tecmmx.edu', 'first' => 'Verónica', 'last' => 'Rangel', 'second' => 'Pineda', 'gender' => 'f', 'birth' => '1990-12-11'],
        ];
        foreach ($teachersData as $data) {
            $person = $createPerson($data['name'], $data['email'], $data['first'], $data['last'], $data['second'], $data['gender'], $data['birth'], ['teacher']);
            $teachers[] = ['person' => $person, 'teacher' => Teacher::where('person_id', $person->id)->first()];
        }

        // --- Tutores (5) ---
        $tutorsData = [
            ['name' => 'Lic. Martha Sánchez Jiménez', 'email' => 'martha.sanchez@tutores.edu', 'first' => 'Martha', 'last' => 'Sánchez', 'second' => 'Jiménez', 'gender' => 'f', 'birth' => '1978-04-19', 'profile' => ['tutor']],
            ['name' => 'Psic. Roberto Camacho Solís', 'email' => 'roberto.camacho@tutores.edu', 'first' => 'Roberto', 'last' => 'Camacho', 'second' => 'Solís', 'gender' => 'm', 'birth' => '1981-06-22', 'profile' => ['tutor']],
            ['name' => 'Lic. Araceli Pineda León', 'email' => 'araceli.pineda@tutores.edu', 'first' => 'Araceli', 'last' => 'Pineda', 'second' => 'León', 'gender' => 'f', 'birth' => '1983-10-05', 'profile' => ['tutor']],
            ['name' => 'Lic. Daniel Vega Hernández', 'email' => 'daniel.vega@tutores.edu', 'first' => 'Daniel', 'last' => 'Vega', 'second' => 'Hernández', 'gender' => 'm', 'birth' => '1976-01-14', 'profile' => ['tutor']],
            ['name' => 'Mtra. Teresa Gutiérrez Franco', 'email' => 'teresa.gutierrez@tutores.edu', 'first' => 'Teresa', 'last' => 'Gutiérrez', 'second' => 'Franco', 'gender' => 'f', 'birth' => '1979-12-27', 'profile' => ['tutor']],
        ];
        $tutorsCollection = [];
        foreach ($tutorsData as $data) {
            $person = $createPerson($data['name'], $data['email'], $data['first'], $data['last'], $data['second'], $data['gender'], $data['birth'], $data['profile']);
            $tutorsCollection[] = ['person' => $person, 'tutor' => Tutor::where('person_id', $person->id)->first()];
        }

        // --- Personas con doble rol (maestro+tutor) ---
        $dualRoles = [
            ['name' => 'Ing. David Ramos Salazar', 'email' => 'david.ramos@dual.edu', 'first' => 'David', 'last' => 'Ramos', 'second' => 'Salazar', 'gender' => 'm', 'birth' => '1980-07-08', 'profile' => ['teacher', 'tutor']],
            ['name' => 'Mtra. Gabriela Parra Millán', 'email' => 'gabriela.parra@dual.edu', 'first' => 'Gabriela', 'last' => 'Parra', 'second' => 'Millán', 'gender' => 'f', 'birth' => '1986-03-18', 'profile' => ['teacher', 'tutor']],
        ];
        $dualTeachersTutors = [];
        foreach ($dualRoles as $data) {
            $person = $createPerson($data['name'], $data['email'], $data['first'], $data['last'], $data['second'], $data['gender'], $data['birth'], $data['profile']);
            $teacher = Teacher::where('person_id', $person->id)->first();
            $tutor = Tutor::where('person_id', $person->id)->first();
            $dualTeachersTutors[] = ['person' => $person, 'teacher' => $teacher, 'tutor' => $tutor];
        }

        // --- Estudiantes (60) con diferentes perfiles académicos ---
        $studentsData = [];
        $maleNames = ['Juan', 'Carlos', 'Luis', 'Miguel', 'José', 'Francisco', 'Javier', 'Alejandro', 'Fernando', 'Gabriel', 'Daniel', 'David', 'Manuel', 'Roberto', 'Ricardo', 'Andrés', 'Diego', 'Sergio', 'Oscar', 'Eduardo'];
        $femaleNames = ['María', 'Ana', 'Laura', 'Sofía', 'Valentina', 'Fernanda', 'Isabella', 'Camila', 'Valeria', 'Ximena', 'Regina', 'Paula', 'Daniela', 'Andrea', 'Natalia', 'Karen', 'Verónica', 'Patricia', 'Beatriz', 'Alejandra'];
        $lastNames = ['García', 'Martínez', 'López', 'Hernández', 'González', 'Pérez', 'Rodríguez', 'Sánchez', 'Ramírez', 'Cruz', 'Flores', 'Vázquez', 'Castillo', 'Morales', 'Jiménez', 'Reyes', 'Gutiérrez', 'Ortiz', 'Ruiz', 'Aguilar'];

        $statuses = ['enrolled', 'enrolled', 'enrolled', 'enrolled', 'on_leave', 'graduated', 'dropped_out']; // mayormente enrolled
        // Asignar tutores de la lista combinada (tutores puros + duales)
        $allTutors = array_merge($tutorsCollection, array_map(function ($item) {
            return ['tutor' => $item['tutor'], 'person' => $item['person']];
        }, $dualTeachersTutors));
        for ($i = 1; $i <= 60; $i++) {
            $gender = rand(0, 1) ? 'm' : 'f';
            $firstName = $gender === 'm' ? $maleNames[array_rand($maleNames)] : $femaleNames[array_rand($femaleNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $secondLastName = $lastNames[array_rand($lastNames)];
            $birthDate = Carbon::now()->subYears(rand(18, 25))->subDays(rand(0, 365))->format('Y-m-d');
            $major = array_rand($majorIds);
            $majorId = $majorIds[$major];
            // GPA: algunos muy buenos (90-100), regulares (70-89), malos (50-69)
            $randGpa = rand(1, 100);
            if ($randGpa <= 20) $gpa = rand(50, 69);       // 20% reprobados / bajo
            elseif ($randGpa <= 60) $gpa = rand(70, 89);    // 40% regular
            else $gpa = rand(90, 100);                     // 40% buenos
            $gpa = round($gpa + (rand(0, 99) / 100), 2);
            $status = $statuses[array_rand($statuses)];
            $tutor = $allTutors[array_rand($allTutors)]['tutor'] ?? null;
            $control = 'A' . str_pad($i, 5, '0', STR_PAD_LEFT) . strtoupper(Str::random(2));

            // Crear usuario y persona con perfil estudiante
            $user = User::firstOrCreate(
                ['email' => "estudiante{$i}@tecmmx.edu"],
                [
                    'name' => $firstName . ' ' . $lastName,
                    'password' => $defaultPassword,
                    'email_verified_at' => now(),
                ]
            );
            $person = Person::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'second_last_name' => $secondLastName,
                    'birth_date' => $birthDate,
                    'gender' => $gender,
                ]
            );
            PersonProfile::firstOrCreate(
                ['person_id' => $person->id, 'profile_type' => 'student']
            );
            $student = Student::firstOrCreate(
                ['person_id' => $person->id],
                [
                    'control_number' => $control,
                    'major_id' => $majorId,
                    'gpa' => $gpa,
                    'tutor_id' => $tutor ? $tutor->id : null,
                    'status' => $status,
                    'is_active' => true,
                ]
            );
            $studentsData[] = ['student' => $student, 'person' => $person, 'major_id' => $majorId, 'gpa' => $gpa, 'tutor_id' => $tutor?->id];
        }

        // --- Triple rol: maestro, tutor y alumno (2 personas) ---
        $tripleRoles = [
            ['first' => 'Eduardo', 'last' => 'Cervantes', 'second' => 'Rosas', 'gender' => 'm', 'birth' => '1996-05-20', 'control' => 'A20201', 'gpa' => 92.5, 'major_name' => 'Ingeniería en Sistemas Computacionales'],
            ['first' => 'Diana', 'last' => 'Navarro', 'second' => 'Ponce', 'gender' => 'f', 'birth' => '1997-08-14', 'control' => 'A20202', 'gpa' => 88.3, 'major_name' => 'Ingeniería en Gestión Empresarial'],
        ];
        $triplePersons = [];
        foreach ($tripleRoles as $data) {
            $name = $data['first'] . ' ' . $data['last'] . ' ' . $data['second'];
            $email = strtolower($data['first'] . '.' . $data['last'] . '@triple.tecmmx.edu');
            $majorId = $majorIds[$data['major_name']];
            $user = User::firstOrCreate(
                ['email' => $email],
                ['name' => $name, 'password' => $defaultPassword, 'email_verified_at' => now()]
            );
            $person = Person::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $data['first'],
                    'last_name' => $data['last'],
                    'second_last_name' => $data['second'],
                    'birth_date' => $data['birth'],
                    'gender' => $data['gender'],
                ]
            );
            // perfiles triple
            PersonProfile::firstOrCreate(['person_id' => $person->id, 'profile_type' => 'teacher']);
            PersonProfile::firstOrCreate(['person_id' => $person->id, 'profile_type' => 'tutor']);
            PersonProfile::firstOrCreate(['person_id' => $person->id, 'profile_type' => 'student']);

            $teacher = Teacher::firstOrCreate(['person_id' => $person->id], ['is_active' => true]);
            $tutor = Tutor::firstOrCreate(['person_id' => $person->id], ['is_active' => true]);
            $student = Student::firstOrCreate(
                ['person_id' => $person->id],
                [
                    'control_number' => $data['control'],
                    'major_id' => $majorId,
                    'gpa' => $data['gpa'],
                    'tutor_id' => null,
                    'status' => 'enrolled',
                    'is_active' => true,
                ]
            );
            $triplePersons[] = ['person' => $person, 'teacher' => $teacher, 'tutor' => $tutor, 'student' => $student];
        }
        // Agregar estos estudiantes a la lista general para ausencias/incidentes
        foreach ($triplePersons as $tp) {
            $studentsData[] = ['student' => $tp['student'], 'person' => $tp['person'], 'major_id' => $tp['student']->major_id, 'gpa' => $tp['student']->gpa, 'tutor_id' => null];
        }

        // --- Crear ausencias (al menos una por estudiante por materia aleatoria, con distintos profesores) ---
        $subjectIds = Subject::pluck('id')->toArray();
        $allTeachers = array_merge($teachers, array_map(function ($d) {
            return ['teacher' => $d['teacher']];
        }, $dualTeachersTutors), array_map(function ($t) {
            return ['teacher' => $t['teacher']];
        }, $triplePersons));
        foreach ($studentsData as $studentData) {
            $student = $studentData['student'];
            // Cada estudiante tiene entre 5 y 15 ausencias en los últimos 3 meses
            $numAbsences = rand(5, 15);
            for ($a = 0; $a < $numAbsences; $a++) {
                $teacher = $allTeachers[array_rand($allTeachers)]['teacher'];
                $subjectId = $subjectIds[array_rand($subjectIds)];
                $date = Carbon::now()->subDays(rand(0, 90))->format('Y-m-d');
                $isJustified = rand(0, 1);
                $reason = $isJustified ? ['Enfermedad', 'Permiso familiar', 'Cita médica', 'Problema personal', 'Trámite escolar'][rand(0, 4)] : null;
                StudentAbsence::firstOrCreate([
                    'student_id' => $student->id,
                    'teacher_id' => $teacher->id,
                    'subject_id' => $subjectId,
                    'date' => $date,
                ], [
                    'is_justified' => $isJustified,
                    'justification_reason' => $reason,
                ]);
            }
        }

        // --- Incidentes (entre 80 y 120) ---
        $categoryIds = IncidentCategory::pluck('id')->toArray();
        // Lista de posibles reporteros: profesores, tutores, duales, triples (personas)
        $reporterPersons = collect();
        foreach ($teachers as $t) {
            $reporterPersons->push($t['person']);
        }
        foreach ($tutorsCollection as $t) {
            $reporterPersons->push($t['person']);
        }
        foreach ($dualTeachersTutors as $d) {
            $reporterPersons->push($d['person']);
        }
        foreach ($triplePersons as $tp) {
            $reporterPersons->push($tp['person']);
        }
        $reporterPersons = $reporterPersons->unique('id')->values();

        $numIncidents = rand(80, 120);
        for ($i = 0; $i < $numIncidents; $i++) {
            $student = $studentsData[array_rand($studentsData)]['student'];
            $reporter = $reporterPersons->random();
            $subject = (rand(1, 10) > 3) ? $subjectIds[array_rand($subjectIds)] : null;
            $categoryId = $categoryIds[array_rand($categoryIds)];
            $risk = rand(1, 3);
            $descOptions = [
                'Interrupción constante',
                'No entregó tarea',
                'Actitud desafiante',
                'Uso de celular',
                'Falta de participación',
                'Burlas a compañero',
                'Incumplimiento de normas',
                'Plagio en trabajo',
                'Retrasos reiterados',
                'Agresión verbal',
            ];
            $description = $descOptions[array_rand($descOptions)] . ' (detalle: ' . Str::random(20) . ')';
            $date = Carbon::now()->subDays(rand(0, 180))->format('Y-m-d');
            StudentIncident::create([
                'student_id' => $student->id,
                'reporter_id' => $reporter->id,
                'subject_id' => $subject,
                'category_id' => $categoryId,
                'risk_level' => $risk,
                'description' => $description,
                'date' => $date,
            ]);
        }

        // --- Observaciones (cada estudiante al menos 2) ---
        foreach ($studentsData as $studentData) {
            $student = $studentData['student'];
            $numObs = rand(2, 5);
            for ($o = 0; $o < $numObs; $o++) {
                $reporter = $reporterPersons->random();
                $texts = [
                    'Participa activamente cuando se le motiva',
                    'Se distrae con facilidad, requiere seguimiento',
                    'Muestra interés en la materia, pero desorganizado',
                    'Es respetuoso y colaborador',
                    'Presenta dificultades en comprensión lectora',
                    'Buen desempeño en trabajos prácticos',
                    'Falta de integración con el grupo',
                    'Proactivo y líder positivo',
                ];
                $description = $texts[array_rand($texts)] . ' (' . Carbon::now()->subDays(rand(0, 60))->format('d/m/Y') . ')';
                StudentObservation::create([
                    'student_id' => $student->id,
                    'reporter_id' => $reporter->id,
                    'description' => $description,
                ]);
            }
        }

        // --- Apoyos (solo tutores, cada estudiante con tutor asignado recibe entre 1 y 3 apoyos) ---
        $actions = [
            'Entrevista psicológica',
            'Tutoría académica',
            'Orientación vocacional',
            'Taller de hábitos de estudio',
            'Reunión con padres',
            'Seguimiento semanal',
            'Plan de mejora',
            'Derivación a psicopedagogía'
        ];
        foreach ($studentsData as $studentData) {
            $student = $studentData['student'];
            if ($student->tutor_id) {
                $numSupports = rand(1, 3);
                for ($s = 0; $s < $numSupports; $s++) {
                    $tutor = Tutor::find($student->tutor_id);
                    $date = Carbon::now()->subDays(rand(0, 120))->format('Y-m-d');
                    StudentSupport::create([
                        'student_id' => $student->id,
                        'tutor_id' => $tutor->id,
                        'action_taken' => $actions[array_rand($actions)],
                        'description' => 'Apoyo proporcionado según lineamiento institucional. Estudiante mostró disposición.',
                        'date' => $date,
                    ]);
                }
            }
        }

        // Agregar un usuario administrador
        User::firstOrCreate(
            ['email' => 'admin@desertector.com'],
            [
                'name' => 'Admin General',
                'password' => $defaultPassword,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Seeder realista completado: carreras, materias, personas, estudiantes, profesores, tutores, ausencias, incidentes, observaciones y apoyos.');
    }
}
