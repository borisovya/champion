<?php

namespace App\Controller;

use App\Repository\PinPongRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/auth/login', name: 'login', methods: 'post')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome!',
            'path' => 'src/Controller/PinPongController.php',
        ]);
    }
}
