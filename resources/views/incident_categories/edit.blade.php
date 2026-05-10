<x-layouts::app :title="__('Editar Categoría de Incidente')">
    <div class="max-w-2xl mx-auto">
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6">
            <h1 class="text-xl font-bold dark:text-white mb-6">Editar Categoría: {{ $incident_category->name }}</h1>

            <form method="POST" action="{{ route('incident_categories.update', $incident_category) }}">
                @csrf
                @method('PUT')

                @include('incident_categories.fields', ['incident_category' => $incident_category])

                <div class="flex justify-end gap-2 mt-8 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <a href="{{ route('incident_categories.index') }}"
                        class="px-4 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 dark:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                        Actualizar categoría
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
