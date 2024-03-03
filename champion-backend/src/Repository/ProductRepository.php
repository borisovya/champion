<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use App\Exception\CategoryNotFoundException;
use App\Exception\ProductNotFoundException;
use App\Model\CrudProductRequest;
use App\Service\FileService;
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
        private readonly CategoryRepository $categoryRepository,
        private readonly FileService $fileService,
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
    public function store(CrudProductRequest $createProductRequest): Product
    {
        $category = $this->categoryRepository->findOrFail($createProductRequest->getCategory());

        $product = (new Product())
            ->setName($createProductRequest->getName())
            ->setStatus($createProductRequest->getStatus())
            ->setDescription($createProductRequest->getDescription())
            ->setCategory($category)
            ->setPrice($createProductRequest->getPrice());

        $this->_em->persist($product);
        $this->_em->flush();

        return $product;
    }

    /**
     * @throws CategoryNotFoundException
     */
    public function reStore(Product $product, CrudProductRequest $updateProductRequest): Product
    {
        $category = $this->categoryRepository->findOrFail($updateProductRequest->getCategory());

        $product
            ->setName($updateProductRequest->getName())
            ->setStatus($updateProductRequest->getStatus())
            ->setDescription($updateProductRequest->getDescription())
            ->setCategory($category)
            ->setPrice($updateProductRequest->getPrice());

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

        $this->_em->flush();

        return $product;
    }

    public function bindImage(Product $product, string $link): Product
    {
        $imagePath = $product->getImage();

        if ($imagePath) {
            $this->fileService->deleteFile($imagePath);
        }

        $product->setImage($link);
        $this->_em->flush();

        return $product;
    }
}
