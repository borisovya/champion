<?php

namespace App\Repository;

use App\Entity\Category;
use App\Enum\CategoryStatus;
use App\Exception\CategoryNotFoundException;
use App\Model\CreateCategoryRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(
        protected ManagerRegistry $registry,
        protected EntityManagerInterface $entityManager,
    ) {
        parent::__construct($registry, Category::class);
    }

    /**
     * @throws CategoryNotFoundException
     */
    public function findOrFail(int $categoryId): Category
    {
        $category = $this->find($categoryId);

        if (!$category) {
            throw new CategoryNotFoundException();
        }

        return $category;
    }

    public function store(CreateCategoryRequest $categoryRequest): Category
    {
        $category = (new Category())
            ->setName($categoryRequest->getName())
            ->setStatus(
                CategoryStatus::from($categoryRequest->getStatus())
            );

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $category;
    }

    public function remove(Category $category): void
    {
        $this->entityManager->remove($category);
        $this->entityManager->flush();
    }

    public function changeStatus(Category $category): Category
    {
        $category->setStatus(CategoryStatus::inverse($category->getStatus()));

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $category;
    }
}
