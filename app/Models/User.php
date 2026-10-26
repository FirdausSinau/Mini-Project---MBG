<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['username', 'nama_lengkap', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function isReporter(): bool
    {
        return $this->role === 'reporter';
    }

    public function isReviewer(): bool
    {
        return $this->role === 'reviewer';
    }

    public function isCoordinator(): bool
    {
        return $this->role === 'coordinator';
    }

    public function isStaff(): bool
    {
        return in_array($this->role, ['reviewer', 'coordinator'], true);
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            'reporter' => 'Pelapor',
            'reviewer' => 'Pemantau',
            'coordinator' => 'Koordinator',
            default => ucfirst((string) $this->role),
        };
    }
}
