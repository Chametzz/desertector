<x-layouts::app :title="__('Gestión de Carreras')">
    <div class="flex flex-col gap-6">
        <!-- Encabezado de la página -->
        <header class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold dark:text-white">Carreras</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Listado de carreras universitarias registradas en el
                    sistema.</p>
            </div>

            <!-- Botón para crear nueva carrera -->
            <flux:button icon="plus" variant="primary" href="{{ route('majors.create') }}" class="cursor-pointer">
                Nueva Carrera
            </flux:button>
        </header>

        <!-- Mensaje de éxito (opcional - puedes agregar un componente de flash message aquí) -->
        @if (session('success'))
            <div class="rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 p-4">
                <p class="text-green-700 dark:text-green-400">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4">
                <p class="text-red-700 dark:text-red-400">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Tabla de contenido -->
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column class="px-6! py-3!">ID</flux:table.column>
                    <flux:table.column class="px-6! py-3!">Nombre de la Carrera</flux:table.column>
                    <flux:table.column class="px-6! py-3!">Fecha de Registro</flux:table.column>
                    <flux:table.column class="px-6! py-3!" align="end">Acciones</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($majors as $major)
                        <flux:table.row>
                            <flux:table.cell class="px-6! py-3! font-mono text-xs text-zinc-500">
                                #{{ $major->id }}
                            </flux:table.cell>

                            <flux:table.cell class="px-6! py-3! font-medium dark:text-white">
                                {{ $major->name }}
                            </flux:table.cell>

                            <flux:table.cell class="!px-6 !py-3 text-zinc-500">
                                {{ $major->created_at->format('d/m/Y') }}
                            </flux:table.cell>

                            <flux:table.cell class="px-6! py-3!">
                                <div class="flex justify-end gap-2">
                                    <!-- Botón Editar -->
                                    <flux:button variant="ghost" icon="pencil-square" inset="top bottom"
                                        class="cursor-pointer" href="{{ route('majors.edit', $major) }}"
                                        aria-label="Editar" />

                                    <!-- Botón Eliminar -->
                                    <flux:modal.trigger name="delete-major-{{ $major->id }}">
                                        <flux:button variant="ghost" icon="trash" inset="top bottom"
                                            class="cursor-pointer text-red-500 hover:text-red-600"
                                            aria-label="Eliminar" />
                                    </flux:modal.trigger>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>

                        <!-- Modal de confirmación para eliminar -->
                        <flux:modal name="delete-major-{{ $major->id }}" class="max-w-sm">
                            <form method="POST" action="{{ route('majors.destroy', $major) }}" class="space-y-6"
                                x-data="{ submitting: false }" x-on:submit="submitting = true">
                                @csrf
                                @method('DELETE')

                                <div class="p-4">
                                    <flux:heading size="lg">¿Eliminar carrera?</flux:heading>
                                    <flux:subheading class="mt-2">
                                        Esta acción no se puede deshacer. Se eliminará permanentemente
                                        <b>{{ $major->name }}</b>.
                                    </flux:subheading>
                                    @if ($major->students()->count() > 0)
                                        <p class="mt-3 text-sm text-red-600 dark:text-red-400">
                                            ⚠️ Esta carrera tiene {{ $major->students()->count() }} estudiante(s)
                                            asignado(s).
                                            Al eliminarla, deberás reasignar estos estudiantes.
                                        </p>
                                    @endif
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
                            <flux:table.cell colspan="4" class="p-0!">
                                <div class="py-12 text-center">
                                    <p class="text-zinc-500 dark:text-zinc-400">No hay carreras registradas aún.</p>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>
    </div>
</x-layouts::app>
