<?php

namespace App\Entity;

use App\Enum\CategoryStatus;
use App\Repository\CategoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[OA\Schema]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column]
    #[OA\Property(
        type: 'int',
        example: 1,
        nullable: false
    )]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[OA\Property(
        type: 'string',
        example: 'test',
    )]
    private string $name;

    #[ORM\Column(type: Types::SMALLINT, enumType: CategoryStatus::class)]
    #[OA\Property(
        type: 'int',
        enum: CategoryStatus::values,
        example: 1,
    )]
    private CategoryStatus $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStatus(): CategoryStatus
    {
        return $this->status;
    }

    public function setStatus(CategoryStatus $status): static
    {
        $this->status = $status;

        return $this;
    }
}
