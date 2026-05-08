<x-layouts::app title="Nueva Materia">
    <flux:container>
        <form method="POST" action="{{ route('subjects.store') }}" x-data="{ submitting: false }" x-on:submit="submitting = true">
            @csrf
            <flux:heading size="xl">Crear Materia</flux:heading>

            <div class="mt-6">
                @include('subjects.fields')
            </div>

            <div class="flex gap-2 mt-6">
                <flux:spacer />
                <flux:button type="submit" variant="primary" class="cursor-pointer" x-bind:disabled="submitting">Guardar Materia</flux:button>
            </div>
        </form>
    </flux:container>
</x-layouts::app>
