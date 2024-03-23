<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Product;
use App\Exception\ProductNotFoundException;
use Doctrine\Common\Collections\ArrayCollection;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Items;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Unique;

class CreateOrderRequest
{
    #[Type('array')]
    #[Count(['min' => 1])]
    #[Unique]
    #[All([
        new Collection(
            fields: [
                'product' => [
                    new Assert\NotBlank(),
                    new Type('int'),
                ],
                'qty' => [
                    new Assert\NotBlank(),
                    new Type('int'),
                ],
            ],
            allowExtraFields: true
        ),
    ])]
    #[OA\Property(
        type: 'array',
        items: new Items(
            properties: [
                new OA\Property(
                    property: 'product',
                    type: 'int',
                    example: 1,
                ),
                new OA\Property(
                    property: 'qty',
                    type: 'int',
                    example: 1,
                ),
            ]
        ),
    )]
    private array $items = [];

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): static
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @throws ProductNotFoundException
     */
    public function getQtyByProduct(Product $product): int
    {
        $collection = new ArrayCollection($this->getItems());

        $items = $collection->filter(fn ($item) => $item['product'] === $product->getId());

        if (0 === $items->count()) {
            throw new ProductNotFoundException();
        }

        return $items->first()['qty'];
    }
}
