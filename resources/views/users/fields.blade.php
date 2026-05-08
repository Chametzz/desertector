<!-- Datos de cuenta -->
<div class="space-y-6">
    <h2 class="text-lg font-semibold dark:text-white border-b pb-3 mb-4">Datos de cuenta</h2>

    <div class="space-y-5">
        <div>
            <label class="block text-sm font-medium mb-2 dark:text-white">Nombre de usuario</label>
            <input type="text" name="name" value="{{ old('name', $user?->name ?? '') }}"
                class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                required>
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-white">Correo electrónico</label>
            <input type="email" name="email" value="{{ old('email', $user?->email ?? '') }}"
                class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                required>
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-white">Contraseña</label>
            <input type="password" name="password"
                class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                {{ isset($isEdit) && $isEdit ? '' : 'required' }}>
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            @if (isset($isEdit) && $isEdit)
                <p class="text-xs text-zinc-500 mt-2">Dejar en blanco para mantener la contraseña actual.</p>
            @endif
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-white">Confirmar contraseña</label>
            <input type="password" name="password_confirmation"
                class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                {{ isset($isEdit) && $isEdit ? '' : 'required' }}>
        </div>
    </div>
</div>

<!-- Datos personales -->
<div class="space-y-6 mt-8">
    <h2 class="text-lg font-semibold dark:text-white border-b pb-3 mb-4">Datos personales</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium mb-2 dark:text-white">Nombre(s)</label>
            <input type="text" name="first_name" value="{{ old('first_name', $user?->person?->first_name ?? '') }}"
                required
                class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            @error('first_name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-white">Apellido paterno</label>
            <input type="text" name="last_name" value="{{ old('last_name', $user?->person?->last_name ?? '') }}"
                required
                class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            @error('last_name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-white">Apellido materno</label>
            <input type="text" name="second_last_name"
                value="{{ old('second_last_name', $user?->person?->second_last_name ?? '') }}"
                class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-white">Fecha de nacimiento</label>
            <input type="date" name="birth_date"
                value="{{ old('birth_date', $user?->person?->birth_date ? $user->person->birth_date->format('Y-m-d') : '') }}"
                required
                class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            @error('birth_date')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-white">Género</label>
            <select name="gender"
                class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="m" {{ old('gender', $user?->person?->gender ?? '') == 'm' ? 'selected' : '' }}>
                    Masculino
                </option>
                <option value="f" {{ old('gender', $user?->person?->gender ?? '') == 'f' ? 'selected' : '' }}>
                    Femenino
                </option>
                <option value="o" {{ old('gender', $user?->person?->gender ?? '') == 'o' ? 'selected' : '' }}>
                    Otro
                </option>
            </select>
        </div>
    </div>
</div>

<!-- Roles -->
<div class="space-y-6 mt-8">
    <h2 class="text-lg font-semibold dark:text-white border-b pb-3 mb-4">Roles</h2>

    <div class="flex flex-wrap gap-6">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="roles[]" value="admin" x-model="roles"
                {{ in_array('admin', $currentRoles ?? []) ? 'checked' : '' }}
                class="w-4 h-4 rounded border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 text-blue-600 focus:ring-blue-500">
            <span class="text-sm dark:text-white">Admin</span>
        </label>

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="roles[]" value="student" x-model="roles"
                {{ in_array('student', $currentRoles ?? []) ? 'checked' : '' }}
                class="w-4 h-4 rounded border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 text-blue-600 focus:ring-blue-500">
            <span class="text-sm dark:text-white">Estudiante</span>
        </label>

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="roles[]" value="teacher" x-model="roles"
                {{ in_array('teacher', $currentRoles ?? []) ? 'checked' : '' }}
                class="w-4 h-4 rounded border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 text-blue-600 focus:ring-blue-500">
            <span class="text-sm dark:text-white">Docente</span>
        </label>

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="roles[]" value="tutor" x-model="roles"
                {{ in_array('tutor', $currentRoles ?? []) ? 'checked' : '' }}
                class="w-4 h-4 rounded border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 text-blue-600 focus:ring-blue-500">
            <span class="text-sm dark:text-white">Tutor</span>
        </label>
    </div>

    @error('roles')
        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
    @enderror

    <!-- Datos específicos de estudiante -->
    <div x-show="roles.includes('student')" x-cloak
        class="mt-6 p-6 border rounded-xl bg-zinc-50 dark:bg-zinc-800/30 space-y-5">
        <h3 class="font-semibold text-base dark:text-white mb-4">Datos del estudiante</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-2 dark:text-white">Número de control</label>
                <input type="text" name="control_number"
                    value="{{ old('control_number', $user?->person?->student?->control_number ?? '') }}"
                    class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2 dark:text-white">Carrera</label>
                <select name="major_id"
                    class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Seleccione una carrera</option>
                    @foreach ($majors as $major)
                        <option value="{{ $major->id }}"
                            {{ old('major_id', $user?->person?->student?->major_id ?? '') == $major->id ? 'selected' : '' }}>
                            {{ $major->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2 dark:text-white">Promedio (0-100)</label>
                <input type="number" step="0.01" min="0" max="100" name="gpa"
                    value="{{ old('gpa', $user?->person?->student?->gpa ?? '') }}"
                    class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2 dark:text-white">Tutor asignado</label>
                <select name="tutor_id"
                    class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Sin tutor</option>
                    @foreach ($tutors as $tutor)
                        <option value="{{ $tutor->id }}"
                            {{ old('tutor_id', $user?->person?->student?->tutor_id ?? '') == $tutor->id ? 'selected' : '' }}>
                            {{ $tutor->person->full_name ?? $tutor->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-2 dark:text-white">Estado académico</label>
                <select name="status"
                    class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="enrolled"
                        {{ old('status', $user?->person?->student?->status ?? '') == 'enrolled' ? 'selected' : '' }}>
                        Enrolled (Cursando)
                    </option>
                    <option value="on_leave"
                        {{ old('status', $user?->person?->student?->status ?? '') == 'on_leave' ? 'selected' : '' }}>
                        On leave (Con permiso)
                    </option>
                    <option value="graduated"
                        {{ old('status', $user?->person?->student?->status ?? '') == 'graduated' ? 'selected' : '' }}>
                        Graduated (Graduado)
                    </option>
                    <option value="dropped_out"
                        {{ old('status', $user?->person?->student?->status ?? '') == 'dropped_out' ? 'selected' : '' }}>
                        Dropped out (Desertor)
                    </option>
                </select>
            </div>
        </div>
    </div>
</div>
