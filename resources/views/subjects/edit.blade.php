<x-layouts::app title="Editar Materia">
    <flux:container>
        <form method="POST" action="{{ route('subjects.update', $subject->id) }}" x-data="{ submitting: false }"
            x-on:submit="submitting = true">
            @csrf
            @method('PUT') {{-- ¡Importante! Laravel necesita esto para editar --}}

            <flux:heading size="xl">Editar Materia: {{ $subject->name }}</flux:heading>

            <div class="mt-6">
                @include('subjects.fields')
            </div>

            <div class="flex gap-2 mt-6">
                <flux:spacer />
                <flux:button type="submit" variant="primary" class="cursor-pointer" x-bind:disabled="submitting">
                    Actualizar Cambios</flux:button>
            </div>
        </form>
    </flux:container>
</x-layouts::app>
