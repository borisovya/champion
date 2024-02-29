<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column]
    #[OA\Property(
        example: 1,
    )]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[OA\Property(
        example: 'product name',
    )]
    private string $name;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[OA\Property(
        example: 'product description',
    )]
    private ?string $description = null;

    #[ORM\Column]
    #[OA\Property(
        example: 1000,
    )]
    private int $price;

    #[ORM\Column]
    #[OA\Property(
        example: true,
    )]
    private bool $status;

    #[ORM\ManyToOne(targetEntity: Category::class, fetch: 'EAGER', inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    #[OA\Property(
        example: 1,
    )]
    private ?Category $category = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Product
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Product
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Product
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): Product
    {
        $this->price = $price;

        return $this;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): Product
    {
        $this->status = $status;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): Product
    {
        $this->category = $category;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): Product
    {
        $this->image = $image;

        return $this;
    }
}
