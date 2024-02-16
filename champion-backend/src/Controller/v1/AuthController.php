<?php

declare(strict_types=1);

namespace App\Controller\v1;

use App\Attribute\RequestBody;
use App\Controller\BaseController;
use App\Exception\UserAlreadyExistsException;
use App\Model\IO\Request\SignUpRequest;
use App\Service\UserService;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends BaseController
{
    /**
     * @throws UserAlreadyExistsException
     */
    #[Route(path: 'auth/sign-up', methods: ['POST'])]
    public function signUp(
        #[RequestBody] SignUpRequest $signUpRequest,
        UserService $authService,
        AuthenticationSuccessHandler $successHandler
    ): Response {
        return $successHandler->handleAuthenticationSuccess(
            $authService->signUp($signUpRequest)
        );
    }
}
