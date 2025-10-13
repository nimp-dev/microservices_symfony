<?php
namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Microservices\SharedContracts\User\CreateUserRequest;
use Microservices\SharedContracts\User\UserResponse;
use Microservices\SharedEvents\UserRegisteredEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{

    public function __construct(
        readonly private UserRepository $userRepository,
        readonly private UserPasswordHasherInterface $passwordHasher,
    ) {}

    #[Route('/create', name: 'user_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        // Данные из запроса
        $data = json_decode($request->getContent(), true);

        // Проверяем существует ли пользователь
        $existingUser = $this->userRepository->findByEmail($data['email']);
        if ($existingUser) {
            return $this->json([
                'error' => 'User with this email already exists'
            ], 409);
        }

        // Создаем DTO из контракта
        $createRequest = CreateUserRequest::fromArray($data);

        // Создаем пользователя
        $user = new User();
        $user->setEmail($createRequest->email);
        $user->setFirstName($createRequest->firstName);
        $user->setLastName($createRequest->lastName);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $createRequest->password);
        $user->setPassword($hashedPassword);

        // Сохраняем в БД
        $this->userRepository->save($user, true);

        // Создаем ответ по контракту
        $userResponse = new UserResponse(
            id: (string)$user->getId(),  // Преобразуем int в string для контракта
            email: $user->getEmail(),
            firstName: $user->getFirstName(),
            lastName: $user->getLastName(),
            status: $user->getStatus(),
            createdAt: $user->getCreatedAt(),
            updatedAt: $user->getUpdatedAt()
        );

        // Создаем событие
        $event = new UserRegisteredEvent(
            eventId: uniqid('event_'),
            userId: (string)$user->getId(),
            email: $user->getEmail(),
            firstName: $user->getFirstName(),
            lastName: $user->getLastName(),
            registeredAt: $user->getCreatedAt()
        );

        return $this->json($userResponse, 201);
    }

    #[Route('/get/{id}', name: 'user_get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        /** @var User|null $user */
        $user = $this->userRepository->find($id);

        if (is_null($user)) {
            return $this->json([
                'error' => 'User not found'
            ], 404);
        }

        $userResponse = new UserResponse(
            id: (string)$user->getId(),
            email: $user->getEmail(),
            firstName: $user->getFirstName(),
            lastName: $user->getLastName(),
            status: $user->getStatus(),
            createdAt: $user->getCreatedAt(),
            updatedAt: $user->getUpdatedAt()
        );

        return $this->json($userResponse);
    }
}
