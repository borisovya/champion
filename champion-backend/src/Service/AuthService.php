<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use App\Model\SignUpRequest;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class AuthService
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher,
        private UserRepository $userRepository
    ) {
    }

    /**
     * @throws UserAlreadyExistsException
     */
    public function register(SignUpRequest $signUpRequest): User
    {
        if ($this->userRepository->exists($signUpRequest->getUsername())) {
            throw new UserAlreadyExistsException();
        }

        $user = (new User())
            ->setRoles($signUpRequest->getRoles())
            ->setUsername($signUpRequest->getUsername())
            ->setTelegramLogin($signUpRequest->getTelegramLogin())
            ->setChampionPartnersLogin($signUpRequest->getChampionPartnersLogin());

        $passwordHash = $this->userPasswordHasher->hashPassword($user, $signUpRequest->getPassword());
        $user->setPassword($passwordHash);
        $this->userRepository->save($user);

        return $user;
    }
}
