<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Order;
use App\Entity\OrderHasProducts;
use App\Entity\Product;
use App\Entity\User;
use App\Exception\OrderNotFoundException;
use App\Exception\ProductNotFoundException;
use App\Exception\ProductsNotFoundException;
use App\Model\CreateOrderRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    private ProductRepository $productRepository;

    public function __construct(
        ManagerRegistry $registry,
        ProductRepository $productRepository,
    ) {
        $this->productRepository = $productRepository;

        parent::__construct($registry, Order::class);
    }

    /**
     * @throws OrderNotFoundException
     */
    public function findOrFail(int $oderId): Order
    {
        $order = $this->find($oderId);

        if (!$order) {
            throw new OrderNotFoundException();
        }

        return $order;
    }

    public function changeStatus(Order $order): Order
    {
        $order->setStatus(!$order->getStatus());

        $this->_em->flush();

        return $order;
    }

    /**
     * @throws ProductNotFoundException
     * @throws ProductsNotFoundException
     */
    public function storeByUser(CreateOrderRequest $request, User $user): Order
    {
        $products = $this->productRepository->findOrFailByIds(
            array_map(
                fn ($item) => $item['product'],
                $request->getItems(),
            )
        );

        $cost = $products->reduce(
            fn (int $acc, Product $product) => $acc + $product->getPrice() * $request->getQtyByProduct($product),
            0
        );

        $order = (new Order())
            ->setStatus(false)
            ->setCost($cost)
            ->setUuid(Uuid::v4())
            ->setUser($user)
            ->setCreatedAt(new \DateTimeImmutable());

        $this->_em->persist($order);

        $orderProducts = new ArrayCollection();

        foreach ($products as $product) {
            $orderProduct = (new OrderHasProducts())
                ->setOrder($order)
                ->setProduct($product)
                ->setQty($request->getQtyByProduct($product))
                ->setItemPrice($product->getPrice())
            ;

            $orderProducts->add($orderProduct);
            $this->_em->persist($orderProduct);
        }

        $order->setItems($orderProducts);
        $this->_em->flush();

        return $order;
    }
}
