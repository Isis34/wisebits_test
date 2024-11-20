<?php

declare(strict_types=1);

namespace App\Handler;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Exception\EntityNotFoundException;
use App\Exception\ValidationException;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserHandler
{
    public function __construct(
        private ValidatorInterface $validator,
        private UserRepository $userRepository,
    ) {
    }

    public function create(UserDTO $DTO): User
    {
        $user = new User($DTO->name, $DTO->email, $DTO->notes);
        $user->setCreated(new \DateTimeImmutable());

        $violations = $this->validator->validate(value: $user);
        if ($violations->count()) {
            throw new ValidationException(message: 'validation errors', violationList: $violations);
        }

        return $user;
    }

    public function update(int $id, UserDTO $DTO): User
    {
        $user = $this->userRepository->findActiveById($id);

        if (! $user) {
            throw new EntityNotFoundException('User with id ' . $id . ' not found');
        }

        $user->setName($DTO->name);
        $user->setEmail($DTO->email);
        $user->setNotes($DTO->notes);

        $violations = $this->validator->validate(value: $user);
        if ($violations->count()) {
            throw new ValidationException(message: 'validation errors', violationList: $violations);
        }

        return $user;
    }

    public function delete(int $userId): void
    {
        $user = $this->userRepository->findActiveById($userId);
        if (! $user) {
            throw new EntityNotFoundException('User with id ' . $userId . ' not found');
        }

        $user->setDeleted(new \DateTimeImmutable('now'));
    }
}
