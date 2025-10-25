<?php

namespace App\Enums;

enum Status
{
    case Inactive;
    case Active;

    public function labelAr(): string
    {
        return match ($this) {
            self::Active => 'فعّال',
            self::Inactive => 'غير فعّال',
        };
    }

    public function labelEn(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
        };
    }

    public static function fromBool(bool $value): self
    {
        return $value ? self::Active : self::Inactive;
    }
}
