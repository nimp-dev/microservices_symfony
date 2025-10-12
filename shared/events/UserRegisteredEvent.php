<?php
namespace Microservices\SharedEvents;

class UserRegisteredEvent implements \JsonSerializable
{
    public function __construct(
        public string $eventId,
        public string $userId,
        public string $email,
        public string $firstName,
        public string $lastName,
        public \DateTimeInterface $registeredAt
    ) {}

    /**
     * @throws \DateMalformedStringException
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['eventId'],
            $data['userId'],
            $data['email'],
            $data['firstName'],
            $data['lastName'],
            new \DateTimeImmutable($data['registeredAt'])
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'eventId' => $this->eventId,
            'userId' => $this->userId,
            'email' => $this->email,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'registeredAt' => $this->registeredAt->format(\DateTimeInterface::ATOM),
        ];
    }
}