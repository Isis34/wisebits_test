<?php

declare(strict_types=1);

namespace App\DTO;

readonly class UserDTO
{
    public function __construct(
        public ?string $name,
        public ?string $email,
        public ?string $notes = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'notes' => $this->notes,
        ];
    }
}
