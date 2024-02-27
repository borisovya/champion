<?php

declare(strict_types=1);

namespace App\Enum;

enum CategoryStatus: int
{
    case ACTIVE = 1;
    case INACTIVE = 0;

    public const values = [
        self::INACTIVE->value,
        self::ACTIVE->value,
    ];

    public static function inverse(self $status): self
    {
        return self::ACTIVE === $status ? self::INACTIVE : self::ACTIVE;
    }
}
