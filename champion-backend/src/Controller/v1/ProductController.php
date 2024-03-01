<?php

namespace App\Controller\v1;

use App\Attribute\RequestBody;
use App\Entity\Product;
use App\Exception\CategoryNotFoundException;
use App\Exception\ProductNotFoundException;
use App\Model\CreateProductRequest;
use App\Model\ErrorResponse;
use App\Repository\ProductRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * Product list.
     */
    #[Route(
        path: '/v1/product',
        name: 'v1_product_index',
        methods: ['GET']
    )]
    #[OA\Tag(name: 'Product')]
    #[OA\Response(
        response: 200,
        description: 'Get product list',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Product::class))
        )
    )]
    public function index(
        ProductRepository $productRepository,
    ): Response {
        return $this->json(
            $productRepository->findAll()
        );
    }

    /**
     * Show product.
     *
     * @throws ProductNotFoundException
     */
    #[Route(
        path: '/v1/product/{productId}',
        name: 'v1_product_show',
        methods: ['GET']
    )]
    #[OA\Tag(name: 'Product')]
    #[OA\Response(
        response: 200,
        description: 'Show product info',
        content: new OA\JsonContent(ref: new Model(type: Product::class))
    )]
    #[OA\Response(response: 404, description: 'Product not found', attachables: [
        new Model(type: ErrorResponse::class),
    ])]
    public function show(
        int $productId,
        ProductRepository $productRepository,
    ): Response {
        return $this->json(
            $productRepository->findOrFail($productId)
        );
    }

    /**
     * Create new product.
     *
     * @throws CategoryNotFoundException
     */
    #[Route(
        path: '/v1/product',
        name: 'v1_product_create',
        methods: ['POST']
    )]
    #[OA\Tag(name: 'Product')]
    #[OA\Response(
        response: 201,
        description: 'Create new product',
        content: new OA\JsonContent(ref: new Model(type: Product::class))
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
        description: 'Category not found',
        attachables: [
            new Model(type: ErrorResponse::class),
        ]
    )]
    #[OA\RequestBody(attachables: [new Model(type: CreateProductRequest::class)])]
    public function create(
        #[RequestBody] CreateProductRequest $createProductRequest,
        ProductRepository $productRepository,
    ): Response {
        return $this->json(
            $productRepository->store($createProductRequest),
            Response::HTTP_CREATED,
        );
    }

    /**
     * Delete product.
     *
     * @throws ProductNotFoundException
     */
    #[Route(
        '/v1/product/{productId}',
        name: 'v1_product_delete',
        methods: ['DELETE']
    )]
    #[OA\Tag(name: 'Product')]
    #[OA\Response(
        response: 204,
        description: 'Product removed'
    )]
    #[OA\Response(
        response: 404,
        description: 'Product not found',
        attachables: [
            new Model(type: ErrorResponse::class),
        ]
    )]
    public function delete(
        int $productId,
        ProductRepository $productRepository,
    ): Response {
        $productRepository->remove($productRepository->findOrFail($productId));

        return $this->json([], status: Response::HTTP_NO_CONTENT);
    }

    /**
     * Toggle product status.
     *
     * @throws ProductNotFoundException
     */
    #[Route(
        '/v1/product/toggle-status/{productId}',
        name: 'v1_product_toggle_status',
        methods: ['PATCH']
    )]
    #[OA\Tag(name: 'Product')]
    #[OA\Response(
        response: 200,
        description: 'Product status changed',
        content: new OA\JsonContent(ref: new Model(type: Product::class))
    )]
    #[OA\Response(
        response: 404,
        description: 'Product not found',
        attachables: [
            new Model(type: ErrorResponse::class),
        ]
    )]
    public function toggleStatus(
        int $productId,
        ProductRepository $productRepository,
    ): Response {
        return $this->json(
            $productRepository->changeStatus(
                $productRepository->findOrFail($productId)
            )
        );
    }
}
