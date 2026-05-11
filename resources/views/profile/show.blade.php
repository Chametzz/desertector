<x-layouts::app title="Inicio">
    <div class="max-w-4xl mx-auto py-6 space-y-6">
        <!-- Tarjeta principal con datos generales -->
        <flux:card class="overflow-hidden">
            <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center gap-4">
                    <div
                        class="w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-2xl font-bold text-blue-700 dark:text-blue-300">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold dark:text-white">{{ $user->name }}</h1>
                        <p class="text-zinc-500 dark:text-zinc-400">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 space-y-4">
                <!-- Datos personales (people) -->
                @if ($user->person)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Nombre completo</p>
                            <p class="text-base dark:text-white">{{ $user->person->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Fecha de nacimiento</p>
                            <p class="text-base dark:text-white">
                                {{ \Carbon\Carbon::parse($user->person->birth_date)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Género</p>
                            <p class="text-base dark:text-white">
                                @switch($user->person->gender)
                                    @case('m')
                                        Masculino
                                    @break

                                    @case('f')
                                        Femenino
                                    @break

                                    @default
                                        Otro
                                @endswitch
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Miembro desde</p>
                            <p class="text-base dark:text-white">{{ $user->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-zinc-500 dark:text-zinc-400">No hay información personal registrada.</p>
                @endif

                <!-- Roles (person_profiles) -->
                <div>
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Roles en el sistema</p>
                    <div class="flex flex-wrap gap-2 mt-1">
                        @forelse($user->person?->personProfiles ?? [] as $profile)
                            <span
                                class="px-2 py-1 text-xs font-semibold rounded-full
                                @switch($profile->profile_type)
                                    @case('admin') bg-purple-100 text-purple-800 @break
                                    @case('teacher') bg-blue-100 text-blue-800 @break
                                    @case('tutor') bg-green-100 text-green-800 @break
                                    @case('student') bg-yellow-100 text-yellow-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch">
                                @switch($profile->profile_type)
                                    @case('admin')
                                        Administrador
                                    @break

                                    @case('teacher')
                                        Docente
                                    @break

                                    @case('tutor')
                                        Tutor
                                    @break

                                    @case('student')
                                        Estudiante
                                    @break

                                    @default
                                        {{ ucfirst($profile->profile_type) }}
                                @endswitch
                            </span>
                            @empty
                                <span class="text-sm text-zinc-500">Sin roles asignados</span>
                            @endforelse
                        </div>
                    </div>

                    <!-- Secciones específicas según roles -->
                    @if ($user->person && $user->person->student)
                        <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4">
                            <h3 class="text-lg font-semibold dark:text-white mb-3">Información de estudiante</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Número de control</p>
                                    <p class="text-base dark:text-white">{{ $user->person->student->control_number }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Carrera</p>
                                    <p class="text-base dark:text-white">{{ $user->person->student->major->name ?? 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Promedio general</p>
                                    <p class="text-base dark:text-white">{{ number_format($user->person->student->gpa, 2) }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Estado académico</p>
                                    <p class="text-base dark:text-white">
                                        @switch($user->person->student->status)
                                            @case('enrolled')
                                                Inscrito
                                            @break

                                            @case('on_leave')
                                                Con permiso
                                            @break

                                            @case('graduated')
                                                Graduado
                                            @break

                                            @case('dropped_out')
                                                Desertor
                                            @break

                                            @default
                                                {{ $user->person->student->status }}
                                        @endswitch
                                    </p>
                                </div>
                                @if ($user->person->student->tutor_id)
                                    <div>
                                        <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Tutor asignado</p>
                                        <p class="text-base dark:text-white">
                                            {{ $user->person->student->tutor->person->full_name ?? 'No asignado' }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if ($user->person && $user->person->teacher)
                        <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4">
                            <h3 class="text-lg font-semibold dark:text-white mb-3">Información de docente</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Estado</p>
                                    <p class="text-base dark:text-white">
                                        {{ $user->person->teacher->is_active ? 'Activo' : 'Inactivo' }}</p>
                                </div>
                                <!-- Aquí podrías agregar más datos propios del docente si los tuvieras -->
                            </div>
                        </div>
                    @endif

                    @if ($user->person && $user->person->tutor)
                        <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4">
                            <h3 class="text-lg font-semibold dark:text-white mb-3">Información de tutor</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Estado</p>
                                    <p class="text-base dark:text-white">
                                        {{ $user->person->tutor->is_active ? 'Activo' : 'Inactivo' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Estudiantes a cargo</p>
                                    <p class="text-base dark:text-white">{{ $user->person->tutor->students->count() }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </flux:card>
        </div>
    </x-layouts::app>
