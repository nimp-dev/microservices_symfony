<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class BaseController extends AbstractController
{
    #[Route('/base/info')]
    public function info(): JsonResponse
    {
        return $this->json(['message' => 'Users API is working!']);
    }
}
