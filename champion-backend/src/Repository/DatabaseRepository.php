<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class DatabaseRepository extends ServiceEntityRepository
{
    public function __construct(
        protected ManagerRegistry $registry,
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct($registry, User::class);
    }

    protected function saveEntity(object $object): void
    {
        $this->entityManager->persist($object);
    }

    protected function saveEntityWithCommit(object $object): void
    {
        $this->saveEntity($object);
        $this->commit();
    }

    protected function deleteEntity(object $object): void
    {
        $this->entityManager->remove($object);
    }

    protected function removeWithCommit(object $object): void
    {
        $this->deleteEntity($object);
        $this->commit();
    }

    private function commit(): void
    {
        $this->entityManager->flush();
    }
}
