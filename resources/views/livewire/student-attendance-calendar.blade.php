<div class="space-y-6">
    <flux:card>
        {{-- Botones para cambiar de modo (solo si tiene ambos roles) --}}
        @if ($canSwitch)
            <div class="flex justify-end mb-4 gap-2">
                <button wire:click="switchMode('teacher')"
                    class="px-4 py-2 rounded-md text-sm font-medium transition
                               {{ $currentMode === 'teacher' ? 'bg-blue-600 text-white' : 'bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-300 dark:hover:bg-zinc-600' }}">
                    Modo Docente
                </button>
                <button wire:click="switchMode('tutor')"
                    class="px-4 py-2 rounded-md text-sm font-medium transition
                               {{ $currentMode === 'tutor' ? 'bg-blue-600 text-white' : 'bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-300 dark:hover:bg-zinc-600' }}">
                    Modo Tutor
                </button>
            </div>
        @else
            {{-- Si no puede cambiar, mostramos una etiqueta con el modo actual --}}
            <div class="flex justify-end mb-4">
                <span
                    class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                    Modo: {{ $currentMode === 'teacher' ? 'Docente' : 'Tutor' }}
                </span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            {{-- Selector de estudiante --}}
            <div>
                <label class="block text-sm font-medium mb-1 dark:text-white">Estudiante</label>
                <select wire:model.live="student_id"
                    class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white px-3 py-2">
                    <option value="">-- Selecciona un alumno --</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}">
                            {{ $student->person->full_name ?? $student->control_number }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Selector de materia --}}
            <div>
                <label class="block text-sm font-medium mb-1 dark:text-white">Materia</label>
                <select wire:model.live="subject_id"
                    class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white px-3 py-2">
                    <option value="">-- Selecciona una materia --</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Navegación del mes --}}
            <div class="flex items-end gap-2">
                <flux:button icon="chevron-left" wire:click="changeMonth(-1)" />
                <div
                    class="flex-1 text-center py-2 border rounded-md bg-zinc-50 dark:bg-zinc-900 font-bold uppercase text-sm">
                    {{ $monthName }}
                </div>
                <flux:button icon="chevron-right" wire:click="changeMonth(1)" />
            </div>
        </div>

        <hr class="mb-6 border-zinc-200 dark:border-zinc-800">

        {{-- Calendario --}}
        <div
            class="grid grid-cols-7 gap-px bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-800 rounded-lg overflow-hidden">
            @foreach (['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $label)
                <div
                    class="bg-zinc-100 dark:bg-zinc-800 p-2 text-center text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase">
                    {{ $label }}
                </div>
            @endforeach

            @for ($i = 1; $i < $firstDayOfMonth; $i++)
                <div class="bg-zinc-50 dark:bg-zinc-900/50 h-24 border border-zinc-100 dark:border-zinc-800"></div>
            @endfor

            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $absence = $absences[$day] ?? null;
                    $hasAbsence = !is_null($absence);
                    $isJustified = $hasAbsence && $absence->is_justified;
                    $disabled = empty($student_id) || empty($subject_id);
                    // Solo puede modificar si está en modo docente, es docente, y hay estudiante/materia seleccionados
                    $canModify = $currentMode === 'teacher' && $isTeacher && !$disabled;
                @endphp
                <div
                    class="relative h-24 border border-zinc-100 dark:border-zinc-800
                    {{ $disabled ? 'bg-zinc-50 dark:bg-zinc-900 cursor-not-allowed' : 'bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-800' }}
                    {{ $hasAbsence ? ($isJustified ? 'bg-emerald-50 dark:bg-emerald-950/30' : 'bg-rose-50 dark:bg-rose-950/30') : '' }}">

                    <div
                        class="absolute top-2 left-2 text-xs font-medium
                        {{ $hasAbsence ? ($isJustified ? 'text-emerald-700 dark:text-emerald-300' : 'text-rose-700 dark:text-rose-300') : 'text-zinc-400 dark:text-zinc-500' }}">
                        {{ $day }}
                    </div>

                    {{-- Botón de toggle (solo modo docente) --}}
                    @if ($canModify)
                        <button wire:click="toggleAbsence({{ $day }})" class="absolute inset-0 w-full h-full"
                            title="Marcar/desmarcar ausencia"></button>
                    @endif

                    @if ($hasAbsence)
                        <div class="flex flex-col items-center justify-center h-full pointer-events-none">
                            <svg class="w-5 h-5 {{ $isJustified ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            @if ($absence->justification_reason)
                                <span
                                    class="text-[10px] mt-1 text-gray-500 dark:text-gray-400 truncate max-w-[90%] px-1">
                                    {{ Str::limit($absence->justification_reason, 15) }}
                                </span>
                            @endif
                        </div>

                        {{-- Botón justificar (solo modo docente) --}}
                        @if ($currentMode === 'teacher' && $isTeacher)
                            <button wire:click.stop="openJustifyModal({{ $absence->id }})"
                                class="absolute bottom-1 right-1 p-1 bg-white dark:bg-zinc-800 rounded-full shadow-sm hover:bg-zinc-100 dark:hover:bg-zinc-700"
                                title="Justificar">
                                <svg class="w-3 h-3 text-gray-600 dark:text-gray-300" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </button>
                        @endif
                    @endif
                </div>
            @endfor
        </div>

        {{-- Mensajes flash --}}
        @if (session()->has('message'))
            <div
                class="mt-4 p-3 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg text-sm">
                {{ session('message') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        @endif
    </flux:card>

    {{-- Modal de justificación (solo modo docente) --}}
    @if ($showJustifyModal && $currentMode === 'teacher' && $isTeacher)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            wire:key="justify-modal">
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-xl max-w-md w-full mx-4 p-6">
                <h3 class="text-lg font-bold mb-4 dark:text-white">Justificar ausencia</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="justify_check" wire:model="justifyIsJustified" class="rounded">
                        <label for="justify_check" class="text-sm dark:text-white">Justificado</label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-white">Razón</label>
                        <textarea wire:model="justifyReason" rows="2"
                            class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white px-3 py-2"
                            placeholder="Motivo de la falta"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" wire:click="$set('showJustifyModal', false)"
                        class="px-4 py-2 border rounded-lg">Cancelar</button>
                    <button type="button" wire:click="deleteFromModal"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Eliminar</button>
                    <button type="button" wire:click="saveJustification"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Guardar</button>
                </div>
            </div>
        </div>
    @endif
</div>
