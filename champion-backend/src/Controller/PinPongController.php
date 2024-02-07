<?php

namespace App\Controller;

use App\Repository\PinPongRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PinPongController extends AbstractController
{
    #[Route('/pin/pong', name: 'app_pin_pong', methods: 'get')]
    public function index(PinPongRepository $pinPongRepository): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome!',
            'path' => 'src/Controller/PinPongController.php',
        ]);
    }
}
