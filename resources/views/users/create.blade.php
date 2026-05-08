<x-layouts::app :title="__('Crear Usuario')">
    <div class="max-w-2xl mx-auto">
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6">
            <h1 class="text-xl font-bold dark:text-white mb-6">Crear Nuevo Usuario</h1>

            <form method="POST" action="{{ route('users.store') }}" x-data="{ roles: [] }">
                @csrf

                @include('users.fields', [
                    'isEdit' => false,
                    'user' => null, // Explícitamente null
                    'majors' => $majors,
                    'tutors' => $tutors,
                    'currentRoles' => [],
                ])

                <div class="flex justify-end gap-2 mt-6">
                    <a href="{{ route('users.index') }}" class="px-4 py-2 rounded-lg border">Cancelar</a>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white">Crear usuario</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
