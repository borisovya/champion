<?php

declare(strict_types=1);

namespace App\Controller\v1;

use App\Attribute\RequestBody;
use App\Controller\BaseController;
use App\Entity\User;
use App\Exception\UserNotFoundException;
use App\Model\ErrorResponse;
use App\Model\UpdateUserRequest;
use App\Repository\UserRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends BaseController
{
    /**
     * Show user.
     *
     * @throws UserNotFoundException
     */
    #[Route('/v1/user/{userId}', name: 'v1_user_show', methods: ['GET'])]
    #[OA\Tag(name: 'User')]
    #[OA\Response(
        response: 200,
        description: 'Show user info',
        content: new OA\JsonContent(ref: new Model(type: User::class))
    )]
    #[OA\Response(response: 404, description: 'User not found', attachables: [
        new Model(type: ErrorResponse::class),
    ])]
    public function show(
        int $userId,
        UserRepository $userRepository,
    ): Response {
        return $this->json(
            $userRepository->findOrFail($userId)
        );
    }

    /**
     * Update user.
     *
     * @throws UserNotFoundException
     */
    #[Route('/v1/user/{userId}', name: 'v1_user_update', methods: ['PATCH'])]
    #[OA\Tag(name: 'User')]
    #[OA\Response(
        response: 200,
        description: 'Update user profile',
        content: new OA\JsonContent(ref: new Model(type: User::class))
    )]
    #[OA\Response(response: 404, description: 'User not found', attachables: [
        new Model(type: ErrorResponse::class),
    ])]
    public function update(
        int $userId,
        #[RequestBody] UpdateUserRequest $updateUserRequest,
        UserRepository $userRepository,
    ): Response {
        $user = $userRepository->findOrFail($userId);

        return $this->json(
            $userRepository->update($user, $updateUserRequest)
        );
    }
}
