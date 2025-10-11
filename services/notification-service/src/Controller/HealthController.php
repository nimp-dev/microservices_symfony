<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class HealthController extends AbstractController
{
    #[Route('/health', name: 'health_check')]
    public function health(): JsonResponse
    {
        return $this->json([
            'status' => 'error',
            'message' => 'test 500 status code'
        ], 500);
    }
}
