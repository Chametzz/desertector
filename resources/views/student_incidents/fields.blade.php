<div class="space-y-5">
    {{-- Estudiante --}}
    <div>
        <label class="block text-sm font-medium mb-1 dark:text-white">Estudiante *</label>
        <select name="student_id" required
            class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white px-4 py-2.5">
            <option value="">Seleccione un estudiante</option>
            @foreach ($students as $student)
                <option value="{{ $student->id }}"
                    {{ old('student_id', $student_incident->student_id ?? '') == $student->id ? 'selected' : '' }}>
                    {{ $student->person->full_name ?? $student->control_number }}
                </option>
            @endforeach
        </select>
        @error('student_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Materia (opcional) --}}
    <div>
        <label class="block text-sm font-medium mb-1 dark:text-white">Materia (opcional)</label>
        <select name="subject_id"
            class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white px-4 py-2.5">
            <option value="">-- Sin materia --</option>
            @foreach ($subjects as $subject)
                <option value="{{ $subject->id }}"
                    {{ old('subject_id', $student_incident->subject_id ?? '') == $subject->id ? 'selected' : '' }}>
                    {{ $subject->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Categoría --}}
    <div>
        <label class="block text-sm font-medium mb-1 dark:text-white">Categoría *</label>
        <select name="category_id" required
            class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white px-4 py-2.5">
            <option value="">Seleccione una categoría</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('category_id', $student_incident->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Nivel de riesgo (1=Bajo,2=Medio,3=Alto) --}}
    <div>
        <label class="block text-sm font-medium mb-1 dark:text-white">Nivel de riesgo *</label>
        <select name="risk_level" required
            class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white px-4 py-2.5">
            <option value="1" {{ old('risk_level', $student_incident->risk_level ?? '') == 1 ? 'selected' : '' }}>
                Bajo (1)</option>
            <option value="2" {{ old('risk_level', $student_incident->risk_level ?? '') == 2 ? 'selected' : '' }}>
                Medio (2)</option>
            <option value="3" {{ old('risk_level', $student_incident->risk_level ?? '') == 3 ? 'selected' : '' }}>
                Alto (3)</option>
        </select>
        @error('risk_level')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Fecha del incidente --}}
    <div>
        <label class="block text-sm font-medium mb-1 dark:text-white">Fecha del incidente *</label>
        <input type="date" name="date"
            value="{{ old('date', optional($student_incident)->date ? $student_incident->date->format('Y-m-d') : date('Y-m-d')) }}"
            required
            class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white px-4 py-2.5">
        @error('date')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Descripción --}}
    <div>
        <label class="block text-sm font-medium mb-1 dark:text-white">Descripción *</label>
        <textarea name="description" rows="4" required
            class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white px-4 py-2.5"
            placeholder="Describe el incidente...">{{ old('description', $student_incident->description ?? '') }}</textarea>
        @error('description')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>
