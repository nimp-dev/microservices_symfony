<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Microservices\SharedEvents\UserRegisteredEvent;

class BaseController extends AbstractController
{
    #[Route('/base/info')]
    public function info(): JsonResponse
    {
        $test = new UserRegisteredEvent(
            '123',
            '456',
            'test@mail.com',
            'John',
            'Dou',
            new \DateTime('now'),
        );

        var_dump($test);
        return $this->json(['message' => 'Users API is working!']);
    }
}
