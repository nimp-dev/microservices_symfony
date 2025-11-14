<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Microservices\SharedEvents\UserRegisteredEvent;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[WithMonologChannel('user_password_hasher')]
class UserPasswordHasher implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $processor,
        private UserPasswordHasherInterface $passwordHasher,
        private MessageBusInterface $messageBus,
        private LoggerInterface $logger,
    ) {}

    /**
     * @throws ExceptionInterface
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (!$data instanceof User) {
            $this->logger->info('❌ Not a User instance, passing through');
            return $this->processor->process($data, $operation, $uriVariables, $context);
        }

        $this->logger->info('🔄 UserPasswordHasher: Processing user creation', [
            'email' => $data->getEmail(),
            'operation' => $operation->getName()
        ]);

        // Хешируем пароль если он предоставлен
        $plainPassword = $data->getPlainPassword();

        if ($plainPassword) {
            $this->logger->info('🔐 Hashing password for user', ['email' => $data->getEmail()]);
            $hashedPassword = $this->passwordHasher->hashPassword($data, $plainPassword);
            $data->setPassword($hashedPassword);
            $data->eraseCredentials();
        }

        $this->logger->info('📦 Calling standard processor to save user');

        try {
            // Обрабатываем через стандартный процессор
            $result = $this->processor->process($data, $operation, $uriVariables, $context);
            $this->logger->info('✅ Standard processor completed successfully');

        } catch (\Exception $e) {
            $this->logger->error('❌ Error in standard processor', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }

        // Отправляем событие только после успешного создания пользователя
        if ($operation->getName() === 'post' || $operation instanceof \ApiPlatform\Metadata\Post) {
            $this->logger->info('🎉 User created successfully, sending event to RabbitMQ', [
                'userId' => $data->getId(),
                'email' => $data->getEmail(),
                'user_exists' => $data->getId() !== null
            ]);

            // Проверяем что у пользователя есть ID (сохранен в БД)
            if ($data->getId() === null) {
                $this->logger->error('❌ User has no ID, cannot send event');
                return $result;
            }

            $event = new UserRegisteredEvent(
                eventId: uniqid('user_reg_', true),
                userId: (string)$data->getId(),
                email: $data->getEmail(),
                firstName: $data->getFirstName(),
                lastName: $data->getLastName(),
                registeredAt: $data->getCreatedAt()
            );

            $this->messageBus->dispatch($event);

            $this->logger->info('✅ Event sent to RabbitMQ', [
                'eventId' => $event->eventId,
                'userId' => $event->userId
            ]);
        } else {
            $this->logger->info('📭 Not a POST operation, skipping event sending', [
                'operation' => $operation->getName()
            ]);
        }

        return $result;
    }
}
