<x-layouts::app :title="__('Editar Usuario')">
    <div class="max-w-2xl mx-auto">
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6">
            <h1 class="text-xl font-bold dark:text-white mb-6">Editar Usuario: {{ $user->name }}</h1>

            <form method="POST" action="{{ route('users.update', $user) }}" x-data="{ roles: {{ json_encode($currentRoles) }} }">
                @csrf
                @method('PUT')

                @include('users.fields', [
                    'isEdit' => true,
                    'user' => $user,
                    'majors' => $majors,
                    'tutors' => $tutors,
                ])

                <div class="flex justify-end gap-2 mt-6">
                    <a href="{{ route('users.index') }}" class="px-4 py-2 rounded-lg border">Cancelar</a>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white">Actualizar
                        usuario</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
