<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Category;
use App\Exception\CategoryNotFoundException;
use App\Model\CreateCategoryRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(protected ManagerRegistry $registry)
    {
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
            ->setStatus($categoryRequest->getStatus());

        $this->_em->persist($category);
        $this->_em->flush();

        return $category;
    }

    public function remove(Category $category): void
    {
        $this->_em->remove($category);
        $this->_em->flush();
    }

    public function changeStatus(Category $category): Category
    {
        $category->setStatus(!$category->getStatus());

        $this->_em->persist($category);
        $this->_em->flush();

        return $category;
    }
}
