<?php
// api/src/State/UserPasswordHasher.php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Microservices\SharedEvents\UserRegisteredEvent;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @implements ProcessorInterface<User, User|void>
 */
class UserPasswordHasher implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface           $processor,
        private UserPasswordHasherInterface  $passwordHasher,
        private readonly MessageBusInterface $messageBus,
    ) {}

    /**
     * @throws ExceptionInterface
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (!$data instanceof User) {
            return $this->processor->process($data, $operation, $uriVariables, $context);
        }

        // Хешируем пароль если он предоставлен
        $plainPassword = $data->getPlainPassword();

        if ($plainPassword) {
            $hashedPassword = $this->passwordHasher->hashPassword(
                $data,
                $plainPassword
            );
            $data->setPassword($hashedPassword);
            $data->eraseCredentials();
        }

        // Обрабатываем через стандартный процессор
        $result = $this->processor->process($data, $operation, $uriVariables, $context);

        // Отправляем событие только после успешного создания пользователя
        if ($operation->getName() === 'post') {
            $event = new UserRegisteredEvent(
                eventId: uniqid('user_reg_', true),
                userId: (string)$data->getId(),
                email: $data->getEmail(),
                firstName: $data->getFirstName(),
                lastName: $data->getLastName(),
                registeredAt: $data->getCreatedAt()
            );

            $this->messageBus->dispatch($event);
        }

        return $result;
    }
}
