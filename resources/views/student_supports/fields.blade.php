<div class="space-y-5">
    {{-- Estudiante --}}
    <div>
        <label class="block text-sm font-medium mb-1 dark:text-white">Estudiante *</label>
        <select name="student_id" required
            class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white px-4 py-2.5">
            <option value="">Seleccione un estudiante</option>
            @foreach ($students as $student)
                <option value="{{ $student->id }}"
                    {{ old('student_id', $student_support->student_id ?? '') == $student->id ? 'selected' : '' }}>
                    {{ $student->person->full_name ?? $student->control_number }}
                </option>
            @endforeach
        </select>
        @error('student_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Acción realizada --}}
    <div>
        <label class="block text-sm font-medium mb-1 dark:text-white">Acción realizada *</label>
        <input type="text" name="action_taken" value="{{ old('action_taken', $student_support->action_taken ?? '') }}"
            required
            class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white px-4 py-2.5"
            placeholder="Ej: Entrevista psicológica, Tutoría grupal, Reunión con padres...">
        @error('action_taken')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Fecha del apoyo --}}
    <div>
        <label class="block text-sm font-medium mb-1 dark:text-white">Fecha del apoyo *</label>
        <input type="date" name="date"
            value="{{ old('date', optional($student_support)->date ? $student_support->date->format('Y-m-d') : date('Y-m-d')) }}"
            required
            class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white px-4 py-2.5">
        @error('date')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Descripción detallada --}}
    <div>
        <label class="block text-sm font-medium mb-1 dark:text-white">Descripción *</label>
        <textarea name="description" rows="4" required
            class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white px-4 py-2.5"
            placeholder="Describe detalladamente el apoyo realizado...">{{ old('description', $student_support->description ?? '') }}</textarea>
        @error('description')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>
