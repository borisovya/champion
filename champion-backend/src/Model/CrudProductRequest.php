<?php

declare(strict_types=1);

namespace App\Model;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints\NotBlank;

class CrudProductRequest
{
    #[NotBlank]
    #[OA\Property(example: 'phone')]
    private string $name;

    #[OA\Property(example: 'phone')]
    private ?string $description = null;

    #[NotBlank]
    #[OA\Property(example: true)]
    private bool $status;

    #[NotBlank]
    #[OA\Property(example: 1000)]
    private int $price;

    #[NotBlank]
    #[OA\Property(example: 1)]
    private int $category;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): CrudProductRequest
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): CrudProductRequest
    {
        $this->price = $price;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): CrudProductRequest
    {
        $this->name = $name;

        return $this;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): CrudProductRequest
    {
        $this->status = $status;

        return $this;
    }

    public function getCategory(): int
    {
        return $this->category;
    }

    public function setCategory(int $category): CrudProductRequest
    {
        $this->category = $category;

        return $this;
    }
}
