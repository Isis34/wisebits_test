<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\UserDTO;
use App\Exception\EntityNotFoundException;
use App\Exception\ValidationException;
use App\Handler\UserHandler;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class UserController extends AbstractController
{
    public function __construct(
        private LoggerInterface $logger,
        private EntityManagerInterface $entityManager,
        private NormalizerInterface $normalizer,
        private UserHandler $userHandler,
        private UserRepository $userRepository,
    ) {

    }

    #[Route('/user/{userId}', methods: ['GET'])]
    public function findUser(int $userId): Response
    {
        $user = $this->userRepository->findActiveById($userId);

        if (! $user) {
            return new JsonResponse(
                [
                    'errors' => ['User not found'],
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        return new JsonResponse($this->normalizer->normalize($user));
    }

    #[Route('/user/{userId}', methods: ['DELETE'])]
    public function deleteUser(int $userId): Response
    {
        try {
            $this->userHandler->delete($userId);
        } catch (EntityNotFoundException $e) {
            return new JsonResponse(
                [
                    'errors' => [$e->getMessage()],
                ],
                status: Response::HTTP_NOT_FOUND
            );
        }

        $this->entityManager->flush();
        $this->logger->info('User was deleted', [
            'id' => $userId,
        ]);
        return new JsonResponse('Successfully deleted');
    }

    #[Route('/user', methods: ['POST'])]
    public function createUser(#[MapRequestPayload] UserDTO $userDTO): JsonResponse
    {
        try {
            $user = $this->userHandler->create($userDTO);
        } catch (ValidationException $e) {
            $this->logger->debug($e);
            return new JsonResponse(
                [
                    'errors' => $e->getDetails(),
                ],
                Response::HTTP_BAD_REQUEST
            );

        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        /** @var array $userData */
        $userData = $this->normalizer->normalize($user);
        $this->logger->info('User was created', $userData);

        return new JsonResponse($userData);
    }

    #[Route('/user/{userId}', methods: ['PUT'])]
    public function updateUser(int $userId, #[MapRequestPayload] UserDTO $userDTO): JsonResponse
    {
        try {
            $user = $this->userHandler->update($userId, $userDTO);
        } catch (EntityNotFoundException $e) {
            return new JsonResponse(
                [
                    'errors' => [$e->getMessage()],
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (ValidationException $e) {
            return new JsonResponse(
                [
                    'errors' => $e->getDetails(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        /** @var array $userData */
        $userData = $this->normalizer->normalize($user);
        $this->entityManager->flush();
        $this->logger->info('User was updated', $userData);

        return new JsonResponse($userData);
    }
}
