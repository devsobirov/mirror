<?php

namespace App\Traits;

use App\Helpers\PhoneNormalizer;

/**
 * @property null|string $phone_number
 * @property null|string $phone_number2
 */
trait HasPhoneNumbers
{
    public function getPhoneNumberAttribute(): ?string
    {
        return PhoneNormalizer::formatPhoneNumber($this->attributes['phone'] ?? null);
    }

    public function getPhoneNumber2Attribute(): ?string
    {
        return PhoneNormalizer::formatPhoneNumber($this->attributes['phone2'] ?? null);
    }
}
