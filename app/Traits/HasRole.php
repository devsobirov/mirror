<?php

namespace App\Traits;

trait HasRole
{
    public const ADMIN = 99;
    public const MANAGER = 88;
    public const WORKER = 66;

    public function isAdmin(): bool
    {
        return $this->role == self::ADMIN;
    }

    public function isWorker(): bool
    {
        return in_array($this->role, [self::WORKER, self::MANAGER]) || $this->isAdmin();
    }

    public function getRoleName(): string
    {
        $roleId = (int)$this->role;

        return match ($roleId) {
            self::ADMIN => 'Admin',
            self::MANAGER => 'Manager',
            self::WORKER => 'Xodim',
            default => 'Maqomsiz'
        };
    }
}
