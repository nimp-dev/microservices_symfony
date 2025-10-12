<?php

namespace Microservices\SharedContracts\Order;

use \DateMalformedStringException;

class OrderResponse implements \JsonSerializable
{
    public function __construct(
        public string $id,
        public string $userId,
        public array $items,
        public string $status = 'pending',
        public float $totalAmount = 0.0,
        public ?\DateTimeInterface $createdAt = null,
        public ?\DateTimeInterface $updatedAt = null
    ) {
        $this->createdAt = $createdAt ?: new \DateTimeImmutable();
        $this->updatedAt = $updatedAt ?: new \DateTimeImmutable();
    }

    /**
     * @throws DateMalformedStringException
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['userId'],
            $data['items'],
            $data['status'] ?? 'pending',
            $data['totalAmount'] ?? 0.0,
            isset($data['createdAt']) ? new \DateTimeImmutable($data['createdAt']) : null,
            isset($data['updatedAt']) ? new \DateTimeImmutable($data['updatedAt']) : null
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'items' => $this->items,
            'status' => $this->status,
            'totalAmount' => $this->totalAmount,
            'createdAt' => $this->createdAt->format(\DateTimeInterface::ATOM),
            'updatedAt' => $this->updatedAt->format(\DateTimeInterface::ATOM),
        ];
    }
}