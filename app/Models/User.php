<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Relación con la tabla People
     */
    public function person(): HasOne
    {
        return $this->hasOne(Person::class, 'user_id');
    }

    /**
     * Acceso directo al perfil de estudiante
     */
    public function student(): ?Student
    {
        return $this->person?->student;
    }

    /**
     * Acceso directo al perfil de docente
     */
    public function teacher(): ?Teacher
    {
        return $this->person?->teacher;
    }

    /**
     * Acceso directo al perfil de tutor
     */
    public function tutor(): ?Tutor
    {
        return $this->person?->tutor;
    }

    /**
     * Obtener todos los perfiles de la persona (como colección)
     */
    public function profiles()
    {
        return $this->person?->personProfiles ?? collect();
    }

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public function hasRole(string $role): bool
    {
        if (!$this->person) {
            return false;
        }

        // Verificar si la relación existe y si el perfil está presente
        return $this->person->personProfiles->contains('profile_type', $role);
    }

    /**
     * Verificar si el usuario es administrador
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Verificar si el usuario es estudiante
     */
    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    /**
     * Verificar si el usuario es docente
     */
    public function isTeacher(): bool
    {
        return $this->hasRole('teacher');
    }

    /**
     * Verificar si el usuario es tutor
     */
    public function isTutor(): bool
    {
        return $this->hasRole('tutor');
    }

    /**
     * Nombre completo del usuario
     */
    public function getFullNameAttribute(): string
    {
        return $this->person?->full_name ?? $this->name;
    }
}
