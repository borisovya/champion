<?php

namespace App\Controller\v1;

use App\Attribute\RequestBody;
use App\Attribute\RequestFile;
use App\Entity\Product;
use App\Enum\UploadType;
use App\Exception\CategoryNotFoundException;
use App\Exception\ProductNotFoundException;
use App\Model\CrudProductRequest;
use App\Model\ErrorResponse;
use App\Repository\ProductRepository;
use App\Service\FileService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

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
    #[OA\RequestBody(attachables: [new Model(type: CrudProductRequest::class)])]
    public function create(
        #[RequestBody] CrudProductRequest $createProductRequest,
        ProductRepository $productRepository,
    ): Response {
        return $this->json(
            $productRepository->store($createProductRequest),
            Response::HTTP_CREATED,
        );
    }

    /**
     * Update product.
     *
     * @throws CategoryNotFoundException
     * @throws ProductNotFoundException
     */
    #[Route(
        path: '/v1/product/{productId}',
        name: 'v1_product_update',
        methods: ['PUT']
    )]
    #[OA\Tag(name: 'Product')]
    #[OA\Response(
        response: 200,
        description: 'Update product',
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
    #[OA\RequestBody(attachables: [new Model(type: CrudProductRequest::class)])]
    public function update(
        int $productId,
        #[RequestBody] CrudProductRequest $updateProductRequest,
        ProductRepository $productRepository,
    ): Response {
        return $this->json(
            $productRepository->reStore(
                $productRepository->findOrFail($productId),
                $updateProductRequest
            ),
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

    /**
     * Bind new image.
     */
    #[Route(
        '/v1/product/bind-image/{productId}',
        name: 'v1_product_bind_image',
        methods: ['POST']
    )]
    #[OA\Tag(name: 'Product')]
    #[OA\Response(
        response: 200,
        description: 'Product with new image',
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
        description: 'Product not found',
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
        int $productId,
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
        ProductRepository $productRepository,
    ): Response {
        $product = $productRepository->findOrFail($productId);

        return $this->json($productRepository->bindImage(
            $product,
            $uploadService->saveFile(
                strtolower(UploadType::PRODUCT->name),
                $file,
            ),
        ));
    }
}
