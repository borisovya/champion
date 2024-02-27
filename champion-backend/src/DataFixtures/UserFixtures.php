<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Enum\Role;
use App\Exception\UserAlreadyExistsException;
use App\Model\SignUpRequest;
use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserService $userService,
        private readonly UserRepository $userRepository,
    ) {
    }

    /**
     * @throws UserAlreadyExistsException
     */
    public function load(ObjectManager $manager): void
    {
        $username = 'admin@gmail.com';

        if ($this->userRepository->exists($username)) {
            return;
        }

        $this->userService->create(
            (new SignUpRequest())
                ->setUsername($username)
                ->setTelegramLogin('@admin')
                ->setChampionPartnersLogin('admin')
                ->setPassword('1q2w3e')
                ->setRoles([
                    Role::SUPER_ADMIN->value,
                ])
        );
    }
}
