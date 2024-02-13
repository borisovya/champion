<?php

declare(strict_types=1);

namespace App\Model\IO;

use App\Model\ErrorDebugDetails;
use App\Model\ErrorValidationDetails;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

class ErrorResponse
{
    public function __construct(private readonly string $message, private readonly mixed $details = null)
    {
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    #[OA\Property(
        type: 'object',
        nullable: true,
        oneOf: [
        new OA\Schema(ref: new Model(type: ErrorDebugDetails::class)),
        new OA\Schema(ref: new Model(type: ErrorValidationDetails::class))]
    )]
    public function getDetails(): mixed
    {
        return $this->details;
    }
}
