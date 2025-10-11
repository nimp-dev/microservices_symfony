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
        throw new \Exception('test');
        return $this->json([
            'status' => 'ok',
            'service' => 'order',
            'timestamp' => time()
        ]);
    }
}
