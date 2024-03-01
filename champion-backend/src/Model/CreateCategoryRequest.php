<?php

declare(strict_types=1);

namespace App\Model;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;

class CreateCategoryRequest
{
    #[NotBlank]
    #[OA\Property(example: 'vehicles')]
    private string $name;

    #[NotNull]
    #[Type('bool')]
    #[OA\Property(example: true)]
    private bool $status;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): CreateCategoryRequest
    {
        $this->name = $name;

        return $this;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): CreateCategoryRequest
    {
        $this->status = $status;

        return $this;
    }
}
