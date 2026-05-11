@props([
    'sidebar' => false,
])

@php
    // Nombre de tu proyecto para no repetirlo
    $projectName = 'Desertector';
@endphp

@if ($sidebar)
    <flux:sidebar.brand name="{{ $projectName }}" {{ $attributes }}>
        <x-slot name="logo" class="flex items-center justify-center">
            {{-- Aquí llamamos a tu nuevo icono --}}
            <x-app-logo-icon class="size-8" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="{{ $projectName }}" {{ $attributes }}>
        <x-slot name="logo" class="flex items-center justify-center">
            {{-- Aquí llamamos a tu nuevo icono --}}
            <x-app-logo-icon class="size-8" />
        </x-slot>
    </flux:brand>
@endif
