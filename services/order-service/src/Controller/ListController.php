<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ListController extends AbstractController
{
    #[Route('/list/get', name: 'order_list')]
    public function get(): JsonResponse
    {
        $info = array_map(function ($item) {
            return [
                'id' => mt_rand(999, 9999),
                'name' => "Item {$item}",
            ];
        }, range(1, 10));

        return $this->json(['message' => $info]);
    }
}
