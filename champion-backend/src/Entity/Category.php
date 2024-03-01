<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[OA\Schema]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue(
        strategy: 'IDENTITY'
    )]
    #[ORM\Column]
    #[OA\Property(
        type: 'int',
        example: 1,
        nullable: false
    )]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[OA\Property(
        example: 'category name',
    )]
    private string $name;

    #[ORM\Column]
    private bool $status;

    #[ORM\OneToMany(
        mappedBy: 'category',
        targetEntity: Product::class,
        cascade: ['remove'],
        orphanRemoval: true
    )]
    #[Ignore]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

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

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }
}
