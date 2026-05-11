<x-layouts::app :title="__('Observaciones de estudiantes')">
    <div class="flex flex-col gap-6">
        <header class="flex items-center justify-between">
            <div>
                <x-title>Observaciones de estudiantes</x-title>
                <x-subtitle>Registro de observaciones cualitativas de los alumnos</x-subtitle>
            </div>
            <flux:button icon="plus" variant="primary" class="cursor-pointer"
                :href="route('student_observations.create')">
                Nueva observación
            </flux:button>
        </header>

        @if (session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif
        @if (session('error'))
            <x-alert type="error">{{ session('error') }}</x-alert>
        @endif

        <div>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column align="center">Fecha</flux:table.column>
                    <flux:table.column align="center">Alumno</flux:table.column>
                    <flux:table.column align="center">Observación</flux:table.column>
                    <flux:table.column align="center">Reportado por</flux:table.column>
                    <flux:table.column align="center">Acciones</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse ($observations as $observation)
                        <flux:table.row>
                            <flux:table.cell align="center">
                                {{ $observation->created_at->format('d/m/Y') }}
                            </flux:table.cell>
                            <flux:table.cell align="center">
                                {{ $observation->student->person->full_name }}
                            </flux:table.cell>
                            <flux:table.cell align="center" class="whitespace-normal wrap-break-word min-w-50">
                                {{ $observation->description }}
                            </flux:table.cell>
                            <flux:table.cell align="center">
                                {{ $observation->reporter->full_name }}
                            </flux:table.cell>
                            <flux:table.cell align="center">
                                <div class="flex justify-center gap-2">
                                    <flux:button variant="ghost" icon="pencil-square" inset="top bottom"
                                        class="cursor-pointer"
                                        href="{{ route('student_observations.edit', $observation) }}"
                                        aria-label="Editar" />
                                    <flux:modal.trigger name="delete-observation-{{ $observation->id }}">
                                        <flux:button variant="ghost" icon="trash" inset="top bottom"
                                            class="cursor-pointer text-red-500 hover:text-red-600"
                                            aria-label="Eliminar" />
                                    </flux:modal.trigger>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>

                        <!-- Modal de confirmación para eliminar -->
                        <flux:modal name="delete-observation-{{ $observation->id }}" class="max-w-sm">
                            <form method="POST" action="{{ route('student_observations.destroy', $observation) }}"
                                class="space-y-6" x-data="{ submitting: false }" x-on:submit="submitting = true">
                                @csrf
                                @method('DELETE')

                                <div class="p-4">
                                    <flux:heading size="lg">¿Eliminar observación?</flux:heading>
                                    <flux:subheading class="mt-2">
                                        Esta acción no se puede deshacer. Se eliminará permanentemente la observación:
                                        <b>{{ Str::limit($observation->description, 50) }}</b>
                                    </flux:subheading>
                                </div>

                                <div class="flex gap-2 p-4 pt-0">
                                    <flux:spacer />
                                    <flux:modal.close>
                                        <flux:button variant="ghost" class="cursor-pointer">Cancelar</flux:button>
                                    </flux:modal.close>
                                    <flux:button type="submit" variant="danger" class="cursor-pointer"
                                        x-bind:disabled="submitting">
                                        Eliminar
                                    </flux:button>
                                </div>
                            </form>
                        </flux:modal>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" align="center">
                                No hay observaciones registradas.
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>
    </div>
</x-layouts::app>
