@php
    $user = Auth::user();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky collapsible="mobile"
        class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.header>
            <x-app-logo sidebar class="mx-auto" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.group class="grid">
                <flux:sidebar.item icon="home" :href="route('profile.show')"
                    :current="request()->routeIs('profile.show')" wire:navigate>
                    Inicio
                </flux:sidebar.item>
                @if ($user->isAdmin())
                    <flux:sidebar.item icon="book-open" :href="route('subjects.index')"
                        :current="request()->routeIs('subjects.*')" wire:navigate>
                        Materias
                    </flux:sidebar.item>
                @endif
                @if ($user->isAdmin())
                    <flux:sidebar.item icon="academic-cap" :href="route('majors.index')"
                        :current="request()->routeIs('majors.*')" wire:navigate>
                        Carreras
                    </flux:sidebar.item>
                @endif
                @if ($user->isAdmin())
                    <flux:sidebar.item icon="user" :href="route('users.index')"
                        :current="request()->routeIs('users.*')" wire:navigate>
                        Usuarios
                    </flux:sidebar.item>
                @endif
                @if ($user->isAdmin() || $user->isTutor() || $user->isTeacher())
                    <flux:sidebar.item icon="calendar-days" :href="route('student_absences.index')"
                        :current="request()->routeIs('student_absences.*')" wire:navigate>
                        Inacistencias
                    </flux:sidebar.item>
                @endif
                @if ($user->isAdmin())
                    <flux:sidebar.item icon="squares-2x2" :href="route('incident_categories.index')"
                        :current="request()->routeIs('incident_categories.*')" wire:navigate>
                        Categorías de Incidentes
                    </flux:sidebar.item>
                @endif
                @if ($user->isAdmin() || $user->isTutor() || $user->isTeacher())
                    <flux:sidebar.item icon="exclamation-triangle" :href="route('student_incidents.index')"
                        :current="request()->routeIs('incidents.*')" wire:navigate>
                        Incidentes
                    </flux:sidebar.item>
                @endif
                @if ($user->isAdmin() || $user->isTutor() || $user->isTeacher())
                    <flux:sidebar.item icon="eye" :href="route('student_observations.index')"
                        :current="request()->routeIs('student_observations.*')" wire:navigate>
                        Observaciones
                    </flux:sidebar.item>
                @endif
                @if ($user->isAdmin() || $user->isTutor())
                    <flux:sidebar.item icon="hand-raised" :href="route('student_supports.index')"
                        :current="request()->routeIs('student_supports.*')" wire:navigate>
                        Apoyos
                    </flux:sidebar.item>
                @endif
                @if ($user->isAdmin() || $user->isTutor())
                    <flux:sidebar.item icon="chart-bar" :href="route('analysis.index')"
                        :current="request()->routeIs('analysis.*')" wire:navigate>
                        Análisis
                    </flux:sidebar.item>
                @endif
            </flux:sidebar.group>
        </flux:sidebar.nav>
        <flux:sidebar.spacer />
        <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer" data-test="logout-button">
                        {{ __('Cerrar sesión') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
</body>

</html>
