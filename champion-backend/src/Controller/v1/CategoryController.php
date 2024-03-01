<?php

declare(strict_types=1);

namespace App\Controller\v1;

use App\Attribute\RequestBody;
use App\Entity\Category;
use App\Exception\CategoryNotFoundException;
use App\Model\CreateCategoryRequest;
use App\Model\ErrorResponse;
use App\Repository\CategoryRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * Category list.
     */
    #[Route(
        path: '/v1/category',
        name: 'v1_category_index',
        methods: ['GET']
    )]
    #[OA\Tag(name: 'Category')]
    #[OA\Response(
        response: 200,
        description: 'Get categories list',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Category::class))
        )
    )]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->json($categoryRepository->findAll());
    }

    /**
     * Create new category.
     */
    #[Route(
        path: '/v1/category',
        name: 'v1_category_create',
        methods: ['POST']
    )]
    #[OA\Tag(name: 'Category')]
    #[OA\Response(
        response: 201,
        description: 'Category created',
        content: new OA\JsonContent(ref: new Model(type: Category::class))
    )]
    #[OA\Response(
        response: 400,
        description: 'Validation failed',
        attachables: [
            new Model(type: ErrorResponse::class),
        ],
    )]
    #[OA\Response(
        response: 404,
        description: 'Category not found',
        attachables: [
            new Model(type: ErrorResponse::class),
        ],
    )]
    #[OA\RequestBody(attachables: [new Model(type: CreateCategoryRequest::class)])]
    public function create(
        #[RequestBody] CreateCategoryRequest $categoryRequest,
        CategoryRepository $categoryRepository,
    ): Response {
        return $this->json(
            $categoryRepository->store($categoryRequest),
            Response::HTTP_CREATED
        );
    }

    /**
     * Delete category.
     *
     * @throws CategoryNotFoundException
     */
    #[Route(
        path: '/v1/category/{categoryId}',
        name: 'v1_category_delete',
        methods: ['DELETE']
    )]
    #[OA\Tag(
        name: 'Category',
    )]
    #[OA\Response(
        response: 204,
        description: 'Category removed',
    )]
    #[OA\Response(
        response: 404,
        description: 'Category not found',
        attachables: [
            new Model(type: ErrorResponse::class),
        ],
    )]
    public function delete(
        int $categoryId,
        CategoryRepository $categoryRepository,
    ): Response {
        $categoryRepository->remove($categoryRepository->findOrFail($categoryId));

        return $this->json([], status: Response::HTTP_NO_CONTENT);
    }

    /**
     * Toggle category status.
     *
     * @throws CategoryNotFoundException
     */
    #[Route(
        path: '/v1/category/toggle-status/{categoryId}',
        name: 'v1_category_toggle_status',
        methods: ['PATCH'],
    )]
    #[OA\Tag(name: 'Category')]
    #[OA\Response(
        response: 200,
        description: 'Category status changed',
        content: new OA\JsonContent(ref: new Model(type: Category::class)),
    )]
    #[OA\Response(
        response: 404,
        description: 'Category not found',
        attachables: [
            new Model(type: ErrorResponse::class),
        ],
    )]
    public function toggleStatus(
        int $categoryId,
        CategoryRepository $categoryRepository,
    ): Response {
        return $this->json(
            $categoryRepository->changeStatus(
                $categoryRepository->findOrFail($categoryId)
            )
        );
    }
}
