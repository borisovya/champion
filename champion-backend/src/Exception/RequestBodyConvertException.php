<?php

declare(strict_types=1);

namespace App\Exception;

class RequestBodyConvertException extends \RuntimeException
{
    public function __construct(\Throwable $previous)
    {
        parent::__construct($previous->getMessage(), 0, $previous);
    }
}
