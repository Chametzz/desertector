<x-layouts::app :title="__('Registrar observación')">
    <div class="max-w-2xl mx-auto">
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6">
            <h1 class="text-xl font-bold dark:text-white mb-6">Registrar nueva observación</h1>

            <form method="POST" action="{{ route('student_observations.store') }}">
                @csrf
                @include('student_observations.fields', ['student_observation' => null])
                <div class="flex justify-end gap-2 mt-8 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <a href="{{ route('student_observations.index') }}"
                        class="px-4 py-2 border rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-700">Cancelar</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Guardar observación
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
