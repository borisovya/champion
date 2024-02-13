<?php

declare(strict_types=1);

namespace App\Controller\v1;

use App\Attribute\RequestBody;
use App\Controller\BaseController;
use App\Model\IO\Request\SignUpRequest;
use App\Service\AuthService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends BaseController
{
    #[Route(path: 'auth/sign-up', methods: ['POST'])]
    public function signUp(
        #[RequestBody] SignUpRequest $signUpRequest,
        AuthService $authService
    ): Response {
        return $this->json($authService->signUp($signUpRequest));
    }
}
