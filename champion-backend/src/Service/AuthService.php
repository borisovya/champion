<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use App\Model\IO\Request\SignUpRequest;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthService extends BaseService
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly UserRepository $userRepository,
        private readonly AuthenticationSuccessHandler $successHandler,
    ) {
    }

    /**
     * @throws UserAlreadyExistsException
     */
    public function signUp(SignUpRequest $signUpRequest): Response
    {
        if (!$this->userRepository->existsByEmail($signUpRequest->getEmail())) {
            throw new UserAlreadyExistsException();
        }

        $user = (new User())
            ->setEmail($signUpRequest->getEmail())
            ->setTelegramLogin($signUpRequest->getTelegramLogin())
            ->setChampionPartnersLogin($signUpRequest->getChampionPartnersLogin());

        $passwordHash = $this->userPasswordHasher->hashPassword($user, $signUpRequest->getPassword());

        $user->setPassword($passwordHash);
        $this->save($user);

        return $this->successHandler->handleAuthenticationSuccess($user);
    }
}
