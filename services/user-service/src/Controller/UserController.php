<?php
namespace App\Controller;


use Microservices\SharedContracts\User\CreateUserRequest;
use Microservices\SharedContracts\User\UserResponse;
use Microservices\SharedEvents\UserRegisteredEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/users', name: 'user_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        // Данные из запроса
        $data = json_decode($request->getContent(), true);

        // Создаем DTO из контракта
        $createRequest = CreateUserRequest::fromArray($data);

        // Бизнес-логика создания пользователя
        $userId = uniqid('user_');

        // Создаем ответ по контракту
        $userResponse = new UserResponse(
            id: $userId,
            email: $createRequest->email,
            firstName: $createRequest->firstName,
            lastName: $createRequest->lastName
        );

        // Создаем событие (пока просто логируем)
        $event = new UserRegisteredEvent(
            eventId: uniqid('event_'),
            userId: $userId,
            email: $createRequest->email,
            firstName: $createRequest->firstName,
            lastName: $createRequest->lastName,
            registeredAt: new \DateTimeImmutable()
        );

        // TODO: Отправить событие в message broker (RabbitMQ)
        error_log("User registered: " . json_encode($event));

        return $this->json($userResponse, 201);
    }

    #[Route('/users/{id}', name: 'user_get', methods: ['GET'])]
    public function get(string $id): JsonResponse
    {
        // TODO: Получить пользователя из базы данных
        // Пока возвращаем mock данные

        $userResponse = new UserResponse(
            id: $id,
            email: 'user@example.com',
            firstName: 'John',
            lastName: 'Doe'
        );

        return $this->json($userResponse);
    }
}
