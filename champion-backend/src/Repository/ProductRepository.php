<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use App\Exception\CategoryNotFoundException;
use App\Exception\ProductNotFoundException;
use App\Model\CreateProductRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private CategoryRepository $categoryRepository,
    ) {
        parent::__construct($registry, Product::class);
    }

    /**
     * @throws ProductNotFoundException
     */
    public function findOrFail(int $productId): Product
    {
        $product = $this->find($productId);

        if (!$product) {
            throw new ProductNotFoundException();
        }

        return $product;
    }

    /**
     * @throws CategoryNotFoundException
     */
    public function store(CreateProductRequest $categoryRequest): Product
    {
        $category = $this->categoryRepository->findOrFail($categoryRequest->getCategory());

        $product = (new Product())
            ->setName($categoryRequest->getName())
            ->setStatus($categoryRequest->getStatus())
            ->setDescription($categoryRequest->getDescription())
            ->setCategory($category)
            ->setPrice($categoryRequest->getPrice());

        $this->_em->persist($product);
        $this->_em->flush();

        return $product;
    }

    public function remove(Product $product): void
    {
        $this->_em->remove($product);
        $this->_em->flush();
    }

    public function changeStatus(Product $product): Product
    {
        $product->setStatus(!$product->getStatus());

        $this->_em->persist($product);
        $this->_em->flush();

        return $product;
    }
}
