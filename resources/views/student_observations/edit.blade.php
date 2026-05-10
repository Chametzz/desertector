<x-layouts::app :title="__('Editar observación')">
    <div class="max-w-2xl mx-auto">
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6">
            <h1 class="text-xl font-bold dark:text-white mb-6">Editar observación #{{ $student_observation->id }}</h1>

            <form method="POST" action="{{ route('student_observations.update', $student_observation) }}">
                @csrf
                @method('PUT')
                @include('student_observations.fields', ['student_observation' => $student_observation])
                <div class="flex justify-end gap-2 mt-8 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <a href="{{ route('student_observations.index') }}"
                        class="px-4 py-2 border rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-700">Cancelar</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Actualizar observación
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
