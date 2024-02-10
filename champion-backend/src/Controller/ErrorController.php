<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class ErrorController
{
    public function show(Throwable $exception, LoggerInterface $logger)
    {
        return new JsonResponse(
            $exception->getMessage(),
            $exception->getCode()
        );
    }
}