@props(['type' => 'success'])

@php
    $colors =
        [
            'success' =>
                'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-700 dark:text-green-400',
            'error' => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-700 dark:text-red-400',
            'info' =>
                'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-400',
        ][$type] ?? $colors['success'];
@endphp

<div {{ $attributes->merge(['class' => "rounded-lg border p-4 $colors"]) }}>
    <p class="text-sm font-medium">
        {{ $slot }}
    </p>
</div>
