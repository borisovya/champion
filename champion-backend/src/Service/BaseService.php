<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class BaseService
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
    ) {
    }

    protected function save(object $object): void
    {
        $this->entityManager->persist($object);
    }

    protected function saveWithCommit(object $object): void
    {
        $this->save($object);
        $this->commit();
    }

    protected function remove(object $object): void
    {
        $this->entityManager->remove($object);
    }

    protected function removeWithCommit(object $object): void
    {
        $this->remove($object);
        $this->commit();
    }

    protected function commit(): void
    {
        $this->entityManager->flush();
    }
}
