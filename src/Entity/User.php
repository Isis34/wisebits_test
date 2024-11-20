<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64, unique: true)]
    private ?string $name;

    #[ORM\Column(length: 256, unique: true)]
    private ?string $email;

    #[ORM\Column]
    private ?DateTimeImmutable $created = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $deleted = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes;

    public function __construct(?string $name, ?string $email, ?string $notes = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->notes = $notes;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCreated(): ?DateTimeImmutable
    {
        return $this->created;
    }

    public function setCreated(DateTimeImmutable $created): static
    {
        $this->created = $created;

        return $this;
    }

    public function getDeleted(): ?DateTimeImmutable
    {
        return $this->deleted;
    }

    public function setDeleted(?DateTimeImmutable $deleted): static
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }
}
