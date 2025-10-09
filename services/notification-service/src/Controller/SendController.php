<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class SendController extends AbstractController
{
    #[Route('send/index')]
    public function index(): JsonResponse
    {
        return $this->json(['message' => 'sending start']);
    }
}
