<x-layouts::app title="Editar Carrera">
    <flux:container>
        <form method="POST" action="{{ route('majors.update', $major->id) }}" x-data="{ submitting: false }"
            x-on:submit="submitting = true">
            @csrf
            @method('PUT')

            <flux:heading size="xl">Editar Carrera: {{ $major->name }}</flux:heading>

            <div class="mt-6">
                @include('majors.fields')
            </div>

            <div class="flex gap-2 mt-6">
                <flux:spacer />
                <flux:button type="submit" variant="primary" class="cursor-pointer" x-bind:disabled="submitting">
                    Actualizar Cambios</flux:button>
            </div>
        </form>
    </flux:container>
</x-layouts::app>
