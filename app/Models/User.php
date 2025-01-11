<?php

namespace App\Models;

use App\Traits\HasRole;
use App\Traits\HasWarehouses;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasRole;
    use HasWarehouses;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getPhoneNumberAttribute()
    {
        // Проверяем, если номер существует, то форматируем его
        $phone = $this->attributes['phone']; // Получаем номер телефона из атрибутов модели
        // Если номер существует и состоит из 9 цифр, применяем формат
        if ($phone && preg_match('/^(\d{9})$/', $phone)) {
            // Форматируем номер: +998(99) 999-99-99
            return "+998 (" . substr($phone, 0, 2) . ") " . substr($phone, 2, 3) . "-" . substr($phone, 5, 2) . "-" . substr($phone, 7, 2);
        }
        return $phone;
    }
}
