<div class="space-y-6">
    <!-- Campo: Nombre -->
    <div>
        <label class="block text-sm font-medium mb-2 dark:text-white">
            Nombre de la categoría <span class="text-red-500">*</span>
        </label>
        <input type="text" name="name" value="{{ old('name', $incident_category->name ?? '') }}" required
            class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="Ej: Conducta, Bajo rendimiento, Acoso">
        @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Campo: Descripción -->
    <div>
        <label class="block text-sm font-medium mb-2 dark:text-white">
            Descripción <span class="text-red-500">*</span>
        </label>
        <textarea name="description" rows="4" required
            class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="Describe en qué consiste esta categoría de incidente...">{{ old('description', $incident_category->description ?? '') }}</textarea>
        @error('description')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
        <p class="text-xs text-zinc-500 mt-1">Máximo 255 caracteres.</p>
    </div>
</div>
