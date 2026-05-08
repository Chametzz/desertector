<x-layouts::app :title="__('Detalles del Usuario')">
    <div class="max-w-2xl mx-auto">
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6">
            <h1 class="text-xl font-bold dark:text-white mb-4">Usuario: {{ $user->name }}</h1>
            <dl class="grid grid-cols-1 gap-3">
                <div>
                    <dt class="font-semibold">Email</dt>
                    <dd>{{ $user->email }}</dd>
                </div>
                <div>
                    <dt class="font-semibold">Nombre completo</dt>
                    <dd>{{ $user->person->full_name ?? $user->name }}</dd>
                </div>
                <div>
                    <dt class="font-semibold">Fecha de nacimiento</dt>
                    <dd>{{ $user->person->birth_date ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold">Género</dt>
                    <dd>{{ ucfirst($user->person->gender ?? '') }}</dd>
                </div>
                <div>
                    <dt class="font-semibold">Roles</dt>
                    <dd>{{ implode(', ', $user->person->personProfiles->pluck('profile_type')->toArray()) }}</dd>
                </div>
                @if ($user->person->student)
                    <div>
                        <dt class="font-semibold">Estudiante</dt>
                        <dd>Control: {{ $user->person->student->control_number }} - Promedio:
                            {{ $user->person->student->gpa }}</dd>
                    </div>
                @endif
            </dl>
            <div class="mt-6 flex gap-2">
                <a href="{{ route('users.edit', $user) }}" class="px-4 py-2 bg-blue-600 text-white rounded">Editar</a>
                <a href="{{ route('users.index') }}" class="px-4 py-2 border rounded">Volver</a>
            </div>
        </div>
    </div>
</x-layouts::app>
