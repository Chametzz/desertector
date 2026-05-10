<div class="space-y-5">
    {{-- Estudiante --}}
    <div>
        <label class="block text-sm font-medium mb-1 dark:text-white">Estudiante *</label>
        <select name="student_id" required
            class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white px-4 py-2.5">
            <option value="">Seleccione un estudiante</option>
            @foreach ($students as $student)
                <option value="{{ $student->id }}"
                    {{ old('student_id', $student_observation->student_id ?? '') == $student->id ? 'selected' : '' }}>
                    {{ $student->person->full_name ?? $student->control_number }}
                </option>
            @endforeach
        </select>
        @error('student_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Descripción --}}
    <div>
        <label class="block text-sm font-medium mb-1 dark:text-white">Observación *</label>
        <textarea name="description" rows="4" required
            class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white px-4 py-2.5"
            placeholder="Escribe aquí tu observación...">{{ old('description', $student_observation->description ?? '') }}</textarea>
        @error('description')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>
