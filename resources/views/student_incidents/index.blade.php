<x-layouts::app title="Incidentes estudiantiles">
    <div class="flex flex-col gap-6">
        <header class="flex items-center justify-between">
            <div>
                <x-title>Incidentes estudiantiles</x-title>
                <x-subtitle>Gestión de incidentes académicos de los alumnos</x-subtitle>
            </div>
            <flux:button icon="plus" variant="primary" class="cursor-pointer" :href="route('student_incidents.create')">
                Nuevo Incidente
            </flux:button>
        </header>
        @if (session('success'))
            <x-alert type="success">
                {{ session('success') }}
            </x-alert>
        @endif

        <div>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column align="center">Fecha</flux:table.column>
                    <flux:table.column align="center">Alumno</flux:table.column>

                    <flux:table.column align="center">Materia</flux:table.column>
                    <flux:table.column align="center">Categoría</flux:table.column>
                    <flux:table.column align="center">Riesgo</flux:table.column>
                    <flux:table.column align="center">Descripción</flux:table.column>
                    <flux:table.column align="center">Reportado por</flux:table.column>
                    <flux:table.column align="center">Acciones</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse ($student_incidents as $student_incident)
                        <flux:table.row>
                            <flux:table.cell align="center">
                                {{ $student_incident->date->format('d/m/Y') }}
                            </flux:table.cell>
                            <flux:table.cell align="center">
                                {{ $student_incident->student->person->full_name }}
                            </flux:table.cell>
                            <flux:table.cell align="center">
                                {{ $student_incident->subject->name ?? '--' }}
                            </flux:table.cell>
                            <flux:table.cell align="center">
                                {{ $student_incident->category->name }}
                            </flux:table.cell>
                            <flux:table.cell align="center">
                                @php
                                    $riskData = [
                                        1 => ['label' => 'Bajo', 'color' => 'green'],
                                        2 => ['label' => 'Medio', 'color' => 'yellow'],
                                        3 => ['label' => 'Alto', 'color' => 'red'],
                                    ][$student_incident->risk_level] ?? ['label' => '--', 'color' => 'zinc'];
                                @endphp

                                <flux:badge color="{{ $riskData['color'] }}" size="sm">
                                    {{ $riskData['label'] }}
                                </flux:badge>
                            </flux:table.cell>
                            <flux:table.cell align="center">
                                {{ $student_incident->description }}
                            </flux:table.cell>
                            <flux:table.cell align="center">
                                {{ $student_incident->reporter->full_name }}
                            </flux:table.cell>
                            <flux:table.cell align="center">
                                <div class="flex justify-center gap-2">
                                    <flux:button variant="ghost" icon="pencil-square" inset="top bottom"
                                        class="cursor-pointer"
                                        href="{{ route('student_incidents.edit', $student_incident) }}"
                                        aria-label="Editar" />
                                    <flux:modal.trigger name="delete-student-incident-{{ $student_incident->id }}">
                                        <flux:button variant="ghost" icon="trash" inset="top bottom"
                                            class="cursor-pointer text-red-500 hover:text-red-600"
                                            aria-label="Eliminar" />
                                    </flux:modal.trigger>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>

                        <!-- Modal de confirmación para eliminar -->
                        <flux:modal name="delete-student-incident-{{ $student_incident->id }}" class="max-w-sm">
                            <form method="POST" action="{{ route('student_incidents.destroy', $student_incident) }}"
                                class="space-y-6" x-data="{ submitting: false }" x-on:submit="submitting = true">
                                @csrf
                                @method('DELETE')

                                <div class="p-4">
                                    <flux:heading size="lg">¿Eliminar incidente?</flux:heading>
                                    <flux:subheading class="mt-2">
                                        Esta acción no se puede deshacer. Se eliminará permanentemente
                                        <b>{{ $student_incident->description }}</b>.
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
                            <flux:table.cell colspan="8" align="center">
                                No hay incidentes registrados.
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>
    </div>
</x-layouts::app>
