<?php

declare(strict_types=1);

namespace App\Model;

use App\Enum\CategoryStatus;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class CreateCategoryRequest
{
    #[NotBlank]
    #[OA\Property(example: 'vehicles')]
    private string $name;

    #[NotBlank]
    #[Choice(CategoryStatus::values)]
    #[OA\Property(enum: CategoryStatus::values, example: CategoryStatus::ACTIVE->value)]
    #[Type('integer')]
    private int|string $status;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): CreateCategoryRequest
    {
        $this->name = $name;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int|string $status): CreateCategoryRequest
    {
        $this->status = $status;

        return $this;
    }
}
