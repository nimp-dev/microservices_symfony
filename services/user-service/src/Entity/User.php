<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'string')]
    private ?string $password = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $firstName = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $lastName = null;

    #[ORM\Column(type: 'string', length: 20)]
    private string $status = 'active';

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    // Геттеры
    public function getId(): ?string { return $this->id; }
    public function getEmail(): ?string { return $this->email; }
    public function getFirstName(): ?string { return $this->firstName; }
    public function getLastName(): ?string { return $this->lastName; }
    public function getPassword(): ?string { return $this->password; }
    public function getStatus(): string { return $this->status; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }

    // Сеттеры
    public function setEmail(string $email): self {
        $this->email = $email;
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function setFirstName(string $firstName): self {
        $this->firstName = $firstName;
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function setLastName(string $lastName): self {
        $this->lastName = $lastName;
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function setPassword(string $password): self {
        $this->password = $password;
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function setStatus(string $status): self {
        $this->status = $status;
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
