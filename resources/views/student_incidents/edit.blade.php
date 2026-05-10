<x-layouts::app :title="__('Editar incidente')">
    <div class="max-w-2xl mx-auto">
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6">
            <h1 class="text-xl font-bold dark:text-white mb-6">Editar incidente #{{ $student_incident->id }}</h1>

            <form method="POST" action="{{ route('student_incidents.update', $student_incident) }}">
                @csrf
                @method('PUT')
                @include('student_incidents.fields', ['student_incident' => $student_incident])
                <div class="flex justify-end gap-2 mt-8 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <a href="{{ route('student_incidents.index') }}"
                        class="px-4 py-2 border rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-700">Cancelar</a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Actualizar
                        incidente</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
