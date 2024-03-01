<?php

declare(strict_types=1);

namespace App\Controller\v1;

use App\Attribute\RequestBody;
use App\Entity\Post;
use App\Exception\PostNotFoundException;
use App\Model\CreatePostRequest;
use App\Model\ErrorResponse;
use App\Repository\PostRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * Post list.
     */
    #[Route(
        path: '/v1/post',
        name: 'v1_post_index',
        methods: ['GET']
    )]
    #[OA\Tag(
        name: 'Post',
    )]
    #[OA\Response(
        response: 200,
        description: 'Get post list',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Post::class))
        )
    )]
    public function index(PostRepository $postRepository): Response
    {
        return $this->json(
            $postRepository->findAll()
        );
    }

    /**
     * Show post.
     *
     * @throws PostNotFoundException
     */
    #[Route(
        path: '/v1/post/{postId}',
        name: 'v1_post_show',
        methods: ['GET']
    )]
    #[OA\Tag(
        name: 'Post',
    )]
    #[OA\Response(
        response: 200,
        description: 'Get post list',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Post::class))
        )
    )]
    public function show(
        int $postId,
        PostRepository $postRepository,
    ): Response {
        return $this->json(
            $postRepository->findOrFail($postId)
        );
    }

    /**
     * Create new post.
     */
    #[Route(
        path: '/v1/post',
        name: 'v1_post_create',
        methods: ['POST'],
    )]
    #[OA\Tag(
        name: 'Post'
    )]
    #[OA\Response(
        response: 201,
        description: 'Create new post',
        content: new OA\JsonContent(ref: new Model(type: Post::class))
    )]
    #[OA\Response(
        response: 400,
        description: 'Validation failed',
        attachables: [
            new Model(type: ErrorResponse::class),
        ]
    )]
    #[OA\RequestBody(
        attachables: [
            new Model(type: CreatePostRequest::class)
        ]
    )]
    public function create(
        #[RequestBody] CreatePostRequest $createPostRequest,
        PostRepository $postRepository,
    ): Response {
        return $this->json(
            $postRepository->store($createPostRequest),
            Response::HTTP_CREATED,
        );
    }

    /**
     * Delete post.
     *
     * @throws PostNotFoundException
     */
    #[Route(
        path: '/v1/post/{postId}',
        name: 'v1_post_delete',
        methods: ['DELETE'],
    )]
    #[OA\Tag(
        name: 'Post',
    )]
    #[OA\Response(
        response: 204,
        description: 'Post removed'
    )]
    #[OA\Response(
        response: 404,
        description: 'Post not found',
        attachables: [
            new Model(type: ErrorResponse::class),
        ]
    )]
    public function delete(
        int $postId,
        PostRepository $postRepository,
    ): Response {
        $postRepository->remove($postRepository->findOrFail($postId));

        return $this->json([], status: Response::HTTP_NO_CONTENT);
    }
}
