<x-layouts::app title="Apoyos estudiantiles">
    <div class="flex flex-col gap-6">
        <header class="flex items-center justify-between">
            <div>
                <x-title>Apoyos estudiantiles</x-title>
                <x-subtitle>Seguimiento de acciones de apoyo realizadas por tutores</x-subtitle>
            </div>
            <flux:button icon="plus" variant="primary" class="cursor-pointer" :href="route('student_supports.create')">
                Nuevo apoyo
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
                    <flux:table.column align="center">Acción tomada</flux:table.column>
                    <flux:table.column align="center">Descripción</flux:table.column>
                    <flux:table.column align="center">Tutor</flux:table.column>
                    <flux:table.column align="center">Acciones</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse ($supports as $support)
                        <flux:table.row>
                            <flux:table.cell align="center">
                                {{ $support->date->format('d/m/Y') }}
                            </flux:table.cell>
                            <flux:table.cell align="center">
                                {{ $support->student->person->full_name }}
                            </flux:table.cell>
                            <flux:table.cell align="center">
                                {{ $support->action_taken }}
                            </flux:table.cell>
                            <flux:table.cell align="center" class="whitespace-normal wrap-break-word min-w-50">
                                {{ $support->description }}
                            </flux:table.cell>
                            <flux:table.cell align="center">
                                {{ $support->tutor->person->full_name }}
                            </flux:table.cell>
                            <flux:table.cell align="center">
                                <div class="flex justify-center gap-2">
                                    <flux:button variant="ghost" icon="pencil-square" inset="top bottom"
                                        class="cursor-pointer" href="{{ route('student_supports.edit', $support) }}"
                                        aria-label="Editar" />
                                    <flux:modal.trigger name="delete-support-{{ $support->id }}">
                                        <flux:button variant="ghost" icon="trash" inset="top bottom"
                                            class="cursor-pointer text-red-500 hover:text-red-600"
                                            aria-label="Eliminar" />
                                    </flux:modal.trigger>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>

                        <!-- Modal de confirmación para eliminar -->
                        <flux:modal name="delete-support-{{ $support->id }}" class="max-w-sm">
                            <form method="POST" action="{{ route('student_supports.destroy', $support) }}"
                                class="space-y-6" x-data="{ submitting: false }" x-on:submit="submitting = true">
                                @csrf
                                @method('DELETE')

                                <div class="p-4">
                                    <flux:heading size="lg">¿Eliminar apoyo?</flux:heading>
                                    <flux:subheading class="mt-2">
                                        Esta acción no se puede deshacer. Se eliminará permanentemente el apoyo:
                                        <b>{{ $support->action_taken }}</b> para el estudiante
                                        <b>{{ $support->student->person->full_name }}</b>.
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
                            <flux:table.cell colspan="6" align="center">
                                No hay apoyos registrados.
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>
    </div>
</x-layouts::app>
