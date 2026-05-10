<?php

namespace App\Policies;

use App\Models\User;
use App\Models\StudentAbsence;

class StudentAbsencePolicy
{
    /**
     * Determinar si el usuario puede crear una ausencia.
     */
    public function create(User $user)
    {
        return $user->isTeacher();
    }

    /**
     * Determinar si el usuario puede actualizar una ausencia específica.
     */
    public function update(User $user, ?StudentAbsence $absence = null)
    {
        if ($user->isTeacher()) {
            // Si no hay modelo específico (ej. en formulario de edición general), permitir acceso
            if (!$absence) {
                return true; // o false, según tu lógica
            }
            return $user->teacher->id === $absence->teacher_id;
        }

        if ($user->isTutor()) {
            if (!$absence) {
                return true;
            }
            return $user->tutor->students->contains($absence->student_id);
        }

        return false;
    }

    /**
     * Determinar si el usuario puede eliminar una ausencia.
     */
    public function delete(User $user, StudentAbsence $absence)
    {
        return $user->isTeacher() && $user->teacher->id === $absence->teacher_id;
    }
}
