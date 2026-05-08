<x-layouts::app :title="__('Gestión de Usuarios')">
    <div class="flex flex-col gap-6">
        <header class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold dark:text-white">Usuarios</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Listado de usuarios registrados en el sistema.</p>
            </div>
            <flux:button icon="plus" variant="primary" href="{{ route('users.create') }}" class="cursor-pointer">
                Nuevo Usuario
            </flux:button>
        </header>

        @if (session('success'))
            <div class="rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 p-4">
                <p class="text-green-700 dark:text-green-400">{{ session('success') }}</p>
            </div>
        @endif

        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column class="!px-6 !py-3">ID</flux:table.column>
                    <flux:table.column class="!px-6 !py-3">Nombre completo</flux:table.column>
                    <flux:table.column class="!px-6 !py-3">Email</flux:table.column>
                    <flux:table.column class="!px-6 !py-3">Roles</flux:table.column>
                    <flux:table.column class="!px-6 !py-3" align="end">Acciones</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($users as $user)
                        <flux:table.row>
                            <flux:table.cell class="!px-6 !py-3 font-mono text-xs text-zinc-500">
                                #{{ $user->id }}
                            </flux:table.cell>
                            <flux:table.cell class="!px-6 !py-3 font-medium dark:text-white">
                                {{ $user->person ? $user->person->full_name : $user->name }}
                            </flux:table.cell>
                            <flux:table.cell class="!px-6 !py-3">{{ $user->email }}</flux:table.cell>
                            <flux:table.cell class="!px-6 !py-3">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($user->person->personProfiles ?? [] as $profile)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200">
                                            {{ ucfirst($profile->profile_type) }}
                                        </span>
                                    @endforeach
                                </div>
                            </flux:table.cell>
                            <flux:table.cell class="!px-6 !py-3">
                                <div class="flex justify-end gap-2">
                                    <flux:button variant="ghost" icon="eye" inset="top bottom"
                                        href="{{ route('users.show', $user) }}" aria-label="Ver" />
                                    <flux:button variant="ghost" icon="pencil-square" inset="top bottom"
                                        href="{{ route('users.edit', $user) }}" aria-label="Editar" />
                                    <flux:modal.trigger name="delete-user-{{ $user->id }}">
                                        <flux:button variant="ghost" icon="trash" inset="top bottom"
                                            class="text-red-500 hover:text-red-600" aria-label="Eliminar" />
                                    </flux:modal.trigger>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>

                        <flux:modal name="delete-user-{{ $user->id }}" class="max-w-sm">
                            <form method="POST" action="{{ route('users.destroy', $user) }}" x-data="{ submitting: false }"
                                x-on:submit="submitting = true">
                                @csrf @method('DELETE')
                                <div class="p-4">
                                    <flux:heading size="lg">¿Eliminar usuario?</flux:heading>
                                    <flux:subheading class="mt-2">
                                        Esta acción eliminará permanentemente a <b>{{ $user->name }}</b> y todos sus
                                        datos asociados.
                                    </flux:subheading>
                                </div>
                                <div class="flex gap-2 p-4 pt-0">
                                    <flux:spacer />
                                    <flux:modal.close>
                                        <flux:button variant="ghost">Cancelar</flux:button>
                                    </flux:modal.close>
                                    <flux:button type="submit" variant="danger" x-bind:disabled="submitting">Eliminar
                                    </flux:button>
                                </div>
                            </form>
                        </flux:modal>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="!p-0">
                                <div class="py-12 text-center">No hay usuarios registrados aún.</div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
            {{ $users->links() }}
        </div>
    </div>
</x-layouts::app>
