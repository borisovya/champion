<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`order_product`')]
class OrderHasProducts
{
    #[ORM\Id]
    #[ORM\GeneratedValue(
        strategy: 'IDENTITY'
    )]
    #[ORM\Column]
    #[Ignore]
    private ?int $id = null;

    #[ORM\ManyToOne(
        targetEntity: Product::class,
        cascade: ['persist', 'remove'],
    )]
    #[ORM\JoinColumn(nullable: false)]
    protected Product $product;

    #[ORM\ManyToOne(
        targetEntity: Order::class,
        cascade: ['persist', 'remove'],
    )]
    #[ORM\JoinColumn(
        name: 'order_id',
        referencedColumnName: 'id',
        nullable: false,
    )]
    #[Ignore]
    protected Order $order;

    #[ORM\Column]
    protected int $qty;

    #[ORM\Column]
    protected int $itemPrice;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Ignore]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Ignore]
    private \DateTimeImmutable $updatedAt;

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): OrderHasProducts
    {
        $this->id = $id;

        return $this;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): static
    {
        $this->order = $order;

        return $this;
    }

    public function getQty(): int
    {
        return $this->qty;
    }

    public function setQty(int $qty): static
    {
        $this->qty = $qty;

        return $this;
    }

    public function getItemPrice(): int
    {
        return $this->itemPrice;
    }

    public function setItemPrice(int $price): static
    {
        $this->itemPrice = $price;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): OrderHasProducts
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): OrderHasProducts
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
