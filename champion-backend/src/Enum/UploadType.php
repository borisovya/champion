<?php

declare(strict_types=1);

namespace App\Enum;

enum UploadType: int
{
    case PRODUCT = 1;

    case POST = 2;
}
