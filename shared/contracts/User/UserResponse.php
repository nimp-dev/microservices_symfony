<?php
namespace Microservices\SharedContracts\User;

class UserResponse implements \JsonSerializable
{
    public function __construct(
        public string $id,
        public string $email,
        public string $firstName,
        public string $lastName,
        public string $status = 'active',
        public ?\DateTimeInterface $createdAt = null,
        public ?\DateTimeInterface $updatedAt = null
    ) {
        $this->createdAt = $createdAt ?: new \DateTimeImmutable();
        $this->updatedAt = $updatedAt ?: new \DateTimeImmutable();
    }

    /**
     * @throws \DateMalformedStringException
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['email'],
            $data['firstName'],
            $data['lastName'],
            $data['status'] ?? 'active',
            isset($data['createdAt']) ? new \DateTimeImmutable($data['createdAt']) : null,
            isset($data['updatedAt']) ? new \DateTimeImmutable($data['updatedAt']) : null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'status' => $this->status,
            'createdAt' => $this->createdAt->format(\DateTimeInterface::ATOM),
            'updatedAt' => $this->updatedAt->format(\DateTimeInterface::ATOM),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}