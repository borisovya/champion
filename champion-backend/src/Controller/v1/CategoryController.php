<?php

namespace App\Controller\v1;

use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * Category list.
     */
    #[Route('/v1/category', name: 'v1_category_list', methods: ['GET'])]
    #[OA\Tag(name: 'Category')]
    public function index(): Response
    {
        return new JsonResponse();
    }

    /**
     * Create new category.
     */
    #[Route('/v1/category', name: 'v1_category_create', methods: ['POST'])]
    #[OA\Tag(name: 'Category')]
    public function create(): Response
    {
        return new JsonResponse();
    }

    /**
     * Delete current category.
     */
    #[Route('/v1/category', name: 'v1_category_delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Category')]
    public function delete(): Response
    {
        return new JsonResponse();
    }

    /**
     * Toggle category status.
     */
    #[Route('/v1/category/toggle-status', name: 'v1_category_toggle_status', methods: ['PATCH'])]
    #[OA\Tag(name: 'Category')]
    public function toggleStatus(): Response
    {
        return new JsonResponse();
    }
}
