<x-layouts::app title="Nueva Carrera">
    <flux:container>
        <form method="POST" action="{{ route('majors.store') }}" x-data="{ submitting: false }" x-on:submit="submitting = true">
            @csrf
            <flux:heading size="xl">Crear Carrera</flux:heading>

            <div class="mt-6">
                @include('majors.fields')
            </div>

            <div class="flex gap-2 mt-6">
                <flux:spacer />
                <flux:button type="submit" variant="primary" class="cursor-pointer" x-bind:disabled="submitting">Guardar
                    Carrera</flux:button>
            </div>
        </form>
    </flux:container>
</x-layouts::app>
