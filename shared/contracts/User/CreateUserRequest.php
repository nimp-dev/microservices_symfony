<?php

namespace Microservices\SharedContracts\User;

class CreateUserRequest implements \JsonSerializable
{
    public function __construct(
        public string $email,
        public string $firstName,
        public string $lastName,
        public string $password
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['email'],
            $data['firstName'],
            $data['lastName'],
            $data['password']
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'email' => $this->email,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'password' => '***' // Never expose password in serialization
        ];
    }
}