<?php

declare(strict_types=1);

namespace App\Controller\v1;

use App\Attribute\RequestBody;
use App\Entity\Order;
use App\Exception\OrderNotFoundException;
use App\Exception\ProductNotFoundException;
use App\Exception\ProductsNotFoundException;
use App\Model\CreateOrderRequest;
use App\Repository\OrderRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class OrderController extends AbstractController
{
    /**
     * Get order list.
     */
    #[Route(
        path: '/v1/order',
        name: 'v1_order_list',
        methods: ['GET']
    )]
    #[OA\Tag(name: 'Order')]
    #[OA\Response(
        response: 200,
        description: 'Get order list',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Order::class))
        )
    )]
    public function index(OrderRepository $orderRepository): Response
    {
        return $this->json(
            $orderRepository->findAll(),
        );
    }

    /**
     * Create order.
     *
     * @throws ProductNotFoundException
     * @throws ProductsNotFoundException
     */
    #[Route(
        path: '/v1/order',
        name: 'v1_order_create',
        methods: ['POST']
    )]
    #[OA\Tag(name: 'Order')]
    #[OA\Response(
        response: 201,
        description: 'Order created',
        content: new OA\JsonContent(ref: new Model(type: Order::class))
    )]
    #[OA\RequestBody(
        required: true,
        attachables: [
            new Model(type: CreateOrderRequest::class),
        ],
    )]
    public function create(
        #[RequestBody] CreateOrderRequest $request,
        #[CurrentUser] $user,
        OrderRepository $orderRepository,
    ): Response {
        return $this->json(
            $orderRepository->storeByUser($request, $user),
            Response::HTTP_CREATED,
        );
    }

    /**
     * Complete order.
     *
     * @throws OrderNotFoundException
     */
    #[Route(
        path: '/v1/order/toggle-status/{orderId}',
        name: 'v1_order_toggle_status',
        methods: ['PATCH']
    )]
    #[OA\Tag(name: 'Order')]
    #[OA\Response(
        response: 201,
        description: 'Order status changed',
        content: new OA\JsonContent(ref: new Model(type: Order::class))
    )]
    public function toggleStatus(
        int $orderId,
        OrderRepository $orderRepository,
    ): Response {
        return $this->json(
            $orderRepository->changeStatus(
                $orderRepository->findOrFail($orderId)
            )
        );
    }
}
