<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Exception\UserNotFoundException;
use App\Model\UpdateUserRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(
        protected ManagerRegistry $registry,
        protected EntityManagerInterface $entityManager,
    ) {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws UserNotFoundException
     */
    public function findOrFail(int $userId): User
    {
        $user = $this->find($userId);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function update(User $user, UpdateUserRequest $dto): User
    {
        $user
            ->setTelegramLogin($dto->getTelegramLogin())
            ->setChampionPartnersLogin($dto->getChampionPartnersLogin());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function exists(string $username): bool
    {
        return null !== $this->findOneBy(['username' => $username]);
    }

    public function save(User $user): User
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
