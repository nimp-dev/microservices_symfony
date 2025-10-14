<?php

namespace App\MessageHandler;

use Microservices\SharedEvents\UserRegisteredEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UserRegisteredEventHandler
{
    public function __construct(
        private LoggerInterface $logger,
    ) {}

    public function __invoke(UserRegisteredEvent $event): void
    {
        $this->logger->info('🎉 User registered event received', [
            'eventId' => $event->eventId,
            'userId' => $event->userId,
            'email' => $event->email,
            'firstName' => $event->firstName,
            'lastName' => $event->lastName,
        ]);

        try {
            // Имитация отправки приветственного email
            $this->sendWelcomeEmail($event->email, $event->firstName, $event->lastName);

            // Имитация отправки push-уведомления
            $this->sendPushNotification($event->userId, $event->firstName);

            $this->logger->info('✅ Notifications sent successfully for user', [
                'userId' => $event->userId,
                'email' => $event->email,
            ]);

        } catch (\Exception $e) {
            $this->logger->error('❌ Failed to send notifications', [
                'userId' => $event->userId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function sendWelcomeEmail(string $email, string $firstName, string $lastName): void
    {
        // Имитация отправки email
        $this->logger->info("📧 Sending welcome email to: {$email}");
        $this->logger->info("👋 Dear {$firstName} {$lastName}, welcome to our platform!");

        // Заглушка для реальной отправки email
        // sleep(1);
    }

    private function sendPushNotification(string $userId, string $firstName): void
    {
        // Имитация отправки push-уведомления
        $this->logger->info("📱 Sending push notification to user: {$userId}");
        $this->logger->info("🔔 Welcome {$firstName}! Your account has been created successfully.");

        // Заглушка для реальной отправки push
        // sleep(1);
    }
}
