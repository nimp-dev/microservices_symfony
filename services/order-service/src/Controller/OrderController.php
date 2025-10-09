<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    #[Route("/orders", name: "order_list")]
    public function index(): JsonResponse
    {
        // Попробуем напрямую создать JsonResponse
        return new JsonResponse(["message" => "Direct AbstractController test"]);
    }
}
