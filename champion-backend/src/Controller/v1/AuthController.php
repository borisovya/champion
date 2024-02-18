<?php

declare(strict_types=1);

namespace App\Controller\v1;

use App\Attribute\RequestBody;
use App\Controller\BaseController;
use App\Exception\UserAlreadyExistsException;
use App\Model\IO\ErrorResponse;
use App\Model\IO\Request\SignUpRequest;
use App\Service\UserService;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends BaseController
{
    /**
     * @throws UserAlreadyExistsException
     */
    #[Route(path: '/auth/sign-up', methods: ['POST'])]
    #[OA\RequestBody(attachables: [new Model(type: SignUpRequest::class)])]
    #[OA\Tag(name: 'Authentication')]
    #[OA\Response(
        response: 200,
        description: 'Signs up a user',
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'token', type: 'string'),
            new OA\Property(property: 'refresh_token', type: 'string'),
        ])
    )]
    #[OA\Response(response: 409, description: 'User already exists', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Response(response: 400, description: 'Validation failed', attachables: [new Model(type: ErrorResponse::class)])]
    public function signUp(
        #[RequestBody] SignUpRequest $signUpRequest,
        UserService $userService,
        AuthenticationSuccessHandler $successHandler,
    ): Response {
        $user = $userService->create($signUpRequest);

        return $successHandler->handleAuthenticationSuccess($user);
    }
}
