<?php

declare(strict_types=1);

namespace App\Controller\v1;

use App\Attribute\RequestBody;
use App\Attribute\RequestFile;
use App\Entity\Post;
use App\Enum\UploadType;
use App\Exception\PostNotFoundException;
use App\Model\ErrorResponse;
use App\Model\PostRequest;
use App\Repository\PostRepository;
use App\Service\FileService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

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
        description: 'Show post info',
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
            new Model(type: PostRequest::class),
        ]
    )]
    public function create(
        #[RequestBody] PostRequest $createPostRequest,
        PostRepository $postRepository,
    ): Response {
        return $this->json(
            $postRepository->store($createPostRequest),
            Response::HTTP_CREATED,
        );
    }

    /**
     * Update post.
     */
    #[Route(
        path: '/v1/post/{postId}',
        name: 'v1_post_update',
        methods: ['PATCH'],
    )]
    #[OA\Tag(
        name: 'Post'
    )]
    #[OA\Response(
        response: 200,
        description: 'Update post',
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
            new Model(type: PostRequest::class),
        ]
    )]
    public function update(
        int $postId,
        #[RequestBody] PostRequest $createPostRequest,
        PostRepository $postRepository,
    ): Response {
        return $this->json(
            $postRepository->reStore(
                $postRepository->findOrFail($postId),
                $createPostRequest
            ),
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

    /**
     * Bind new image.
     */
    #[Route(
        path: '/v1/post/bind-image/{postId}',
        name: 'v1_post_bind_image',
        methods: ['POST'],
    )]
    #[OA\Tag(name: 'Post')]
    #[OA\Response(
        response: 200,
        description: 'Post with new image',
        content: new OA\JsonContent(ref: new Model(type: Post::class))
    )]
    #[OA\Response(
        response: 400,
        description: 'Validation failed',
        attachables: [
            new Model(type: ErrorResponse::class),
        ]
    )]
    #[OA\Response(
        response: 404,
        description: 'Post not found',
        attachables: [
            new Model(type: ErrorResponse::class),
        ]
    )]
    #[OA\RequestBody(
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'file', type: 'file', format: 'binary'),
                ]
            )
        )
    )]
    public function bindImage(
        int $postId,
        #[RequestFile(
            field: 'file',
            constraints: [
                new NotNull(),
                new Image(
                    maxSize: '5M',
                    mimeTypes: [
                        'image/jpeg',
                        'image/png',
                        'image/jpg',
                    ],
                ),
            ]
        )] UploadedFile $file,
        FileService $uploadService,
        PostRepository $postRepository,
    ): Response {
        $post = $postRepository->findOrFail($postId);

        return $this->json(
            $postRepository->bindImage(
                $post,
                $uploadService->saveFile(
                    strtolower(UploadType::POST->name),
                    $file,
                ),
            )
        );
    }
}
