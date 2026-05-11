<x-layouts::app title="Análisis de Alumnos">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Análisis de Alumnos</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Resumen de los estudiantes a cargo del tutor
                inscritos y activos).</p>
        </div>

        {{-- Tabla resumen por nivel de riesgo --}}
        <flux:card class="mb-8">
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-700">
                <flux:heading size="lg">Resumen por nivel de riesgo</flux:heading>
            </div>
            <div class="p-0">
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Nivel de Riesgo</flux:table.column>
                        <flux:table.column align="center">Cantidad de Alumnos</flux:table.column>
                        <flux:table.column align="center">Porcentaje</flux:table.column>
                        <flux:table.column align="center">Promedio de calificaciones</flux:table.column>
                    </flux:table.columns>
                    <flux:table.rows>
                        <flux:table.row>
                            <flux:table.cell>
                                <flux:badge color="red">Alto</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell align="center">{{ $riskCounts['Alto'] }}</flux:table.cell>
                            <flux:table.cell align="center">{{ $riskPercentages['Alto'] }}%</flux:table.cell>
                            <flux:table.cell align="center">{{ $avgGpaByRisk['Alto'] }}</flux:table.cell>
                        </flux:table.row>
                        <flux:table.row>
                            <flux:table.cell>
                                <flux:badge color="yellow">Medio</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell align="center">{{ $riskCounts['Medio'] }}</flux:table.cell>
                            <flux:table.cell align="center">{{ $riskPercentages['Medio'] }}%</flux:table.cell>
                            <flux:table.cell align="center">{{ $avgGpaByRisk['Medio'] }}</flux:table.cell>
                        </flux:table.row>
                        <flux:table.row>
                            <flux:table.cell>
                                <flux:badge color="green">Bajo</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell align="center">{{ $riskCounts['Bajo'] }}</flux:table.cell>
                            <flux:table.cell align="center">{{ $riskPercentages['Bajo'] }}%</flux:table.cell>
                            <flux:table.cell align="center">{{ $avgGpaByRisk['Bajo'] }}</flux:table.cell>
                        </flux:table.row>
                        <flux:table.row>
                            <flux:table.cell class="font-semibold">Total</flux:table.cell>
                            <flux:table.cell align="center" class="font-semibold">{{ $totalStudents }}</flux:table.cell>
                            <flux:table.cell align="center" class="font-semibold">100%</flux:table.cell>
                            <flux:table.cell align="center" class="font-semibold">
                                {{ round(array_sum($avgGpaByRisk) / 3, 2) }}</flux:table.cell>
                        </flux:table.row>
                    </flux:table.rows>
                </flux:table>
            </div>
        </flux:card>

        {{-- Tabla detallada de alumnos --}}
        <flux:card>
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-700">
                <flux:heading size="lg">Listado de Alumnos</flux:heading>
            </div>
            <div class="overflow-x-auto">
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Alumno</flux:table.column>
                        <flux:table.column>Número de Control</flux:table.column>
                        <flux:table.column>Carrera</flux:table.column>
                        <flux:table.column>Estado</flux:table.column>
                        <flux:table.column align="center">Promedio</flux:table.column>
                        <flux:table.column align="center">Inasistencias (No justif.)</flux:table.column>
                        <flux:table.column align="center">Incidentes</flux:table.column>
                        <flux:table.column align="center">Observaciones</flux:table.column>
                        <flux:table.column>Riesgo</flux:table.column>
                    </flux:table.columns>
                    <flux:table.rows>
                        @forelse($studentsData as $student)
                            <flux:table.row>
                                <flux:table.cell>{{ $student['full_name'] }}</flux:table.cell>
                                <flux:table.cell>{{ $student['control_number'] }}</flux:table.cell>
                                <flux:table.cell>{{ $student['major'] }}</flux:table.cell>
                                <flux:table.cell>
                                    @switch($student['status'])
                                        @case('enrolled')
                                            Inscrito
                                        @break

                                        @case('on_leave')
                                            Con permiso
                                        @break

                                        @case('graduated')
                                            Graduado
                                        @break

                                        @case('dropped_out')
                                            Desertor
                                        @break

                                        @default
                                            {{ $student['status'] }}
                                    @endswitch
                                </flux:table.cell>
                                <flux:table.cell align="center">{{ number_format($student['gpa'], 2) }}
                                </flux:table.cell>
                                <flux:table.cell align="center">{{ $student['unjustified_absences'] }}
                                </flux:table.cell>
                                <flux:table.cell align="center">{{ $student['incidents_count'] }}</flux:table.cell>
                                <flux:table.cell align="center">{{ $student['observations_count'] }}</flux:table.cell>
                                <flux:table.cell>
                                    @php
                                        $badgeColor = match ($student['risk_label']) {
                                            'Alto' => 'red',
                                            'Medio' => 'yellow',
                                            default => 'green',
                                        };
                                    @endphp
                                    <flux:badge :color="$badgeColor">{{ $student['risk_label'] }}</flux:badge>
                                </flux:table.cell>
                            </flux:table.row>
                            @empty
                                <flux:table.row>
                                    <flux:table.cell colspan="9" class="text-center">No hay alumnos activos asignados a
                                        tu tutoría.</flux:table.cell>
                                </flux:table.row>
                            @endforelse
                        </flux:table.rows>
                    </flux:table>
                </div>
            </flux:card>

            {{-- Nota aclaratoria --}}
            <div class="mt-6 text-xs text-gray-500 dark:text-gray-400">
                <p><strong>Nota:</strong> El nivel de riesgo se calcula a partir del incidente más grave reportado para cada
                    alumno (riesgo 3 = Alto, 2 = Medio, 1 o ningún incidente = Bajo). Las inasistencias no justificadas, el
                    bajo promedio y el número de incidentes influyen indirectamente en el ordenamiento, pero el color
                    principal se basa en el incidente de mayor riesgo.</p>
            </div>
        </div>
    </x-layouts::app>
