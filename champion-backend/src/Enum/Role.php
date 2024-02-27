<?php

declare(strict_types=1);

namespace App\Enum;

enum Role: string
{
    case USER = 'ROLE_USER';

    case ADMIN = 'ROLE_ADMIN';

    case SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    public const values = [
        self::USER->value,
        self::ADMIN->value,
        self::SUPER_ADMIN->value,
    ];
}
