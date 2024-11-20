<?php

declare(strict_types=1);

namespace App\Tests\unit\Handler;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Exception\EntityNotFoundException;
use App\Exception\ValidationException;
use App\Handler\UserHandler;
use App\Repository\UserRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserHandlerTest extends TestCase
{
    private $validator;

    private $userRepository;

    protected function setUp(): void
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        parent::setUp();
    }

    public static function getUserData(): array
    {
        return [
            [1, 'name', 'ma@mail.com'],
        ];
    }

    /**
     * @dataProvider getUserData
     */
    public function testUpdate(int $id, string $name, string $email)
    {
        $userDTO = new UserDTO($name, $email);
        $user = $this->createMock(User::class);
        $this->mockFindActiveRepoMethod($id, $user);

        $user->expects($this->once())->method('setEmail')->with($email);
        $user->expects($this->once())->method('setName')->with($name);
        $user->expects($this->once())->method('setNotes')->with(null);

        $violationList = $this->createMock(ConstraintViolationListInterface::class);
        $violationList->expects($this->once())->method('count')->willReturn(0);
        $this->validator->expects($this->once())->method('validate')->willReturn($violationList);
        $userHandler = new UserHandler($this->validator, $this->userRepository);

        $this->assertSame($user, $userHandler->update($id, $userDTO));
    }

    /**
     * @dataProvider getUserData
     */
    public function testUpdateFailed(int $id, string $name, string $email)
    {
        $userDTO = new UserDTO($name, $email);
        $this->mockFindActiveRepoMethod($id, null);

        $this->validator->expects($this->never())->method('validate');
        $userHandler = new UserHandler($this->validator, $this->userRepository);

        $this->expectException(EntityNotFoundException::class);
        $userHandler->update($id, $userDTO);
    }

    /**
     * @dataProvider getUserData
     */
    public function testUpdateFailedDueValidation(int $id, string $name, string $email)
    {
        $userDTO = new UserDTO($name, $email);
        $user = $this->createMock(User::class);
        $this->mockFindActiveRepoMethod($id, $user);

        $user->expects($this->once())->method('setEmail')->with($email);
        $user->expects($this->once())->method('setName')->with($name);
        $user->expects($this->once())->method('setNotes')->with(null);

        $violationList = $this->createMock(ConstraintViolationListInterface::class);
        $violationList->expects($this->once())->method('count')->willReturn(1);
        $this->validator->expects($this->once())->method('validate')->willReturn($violationList);
        $userHandler = new UserHandler($this->validator, $this->userRepository);

        $this->expectException(ValidationException::class);
        $userHandler->update($id, $userDTO);
    }

    /**
     * @dataProvider getUserData
     */
    public function testDelete(int $id)
    {
        $user = $this->createMock(User::class);
        $this->mockFindActiveRepoMethod($id, $user);

        $user->expects($this->once())
            ->method('setDeleted')
            ->with(self::isInstanceOf(\DateTimeImmutable::class));
        $userHandler = new UserHandler($this->validator, $this->userRepository);

        $userHandler->delete($id);
    }

    /**
     * @dataProvider getUserData
     */
    public function testDeleteFailed(int $id)
    {
        $this->mockFindActiveRepoMethod($id, null);

        $userHandler = new UserHandler($this->validator, $this->userRepository);

        $this->expectException(EntityNotFoundException::class);
        $userHandler->delete($id);
    }

    public function testCreate()
    {
        $violationList = $this->createMock(ConstraintViolationListInterface::class);
        $this->validator->expects($this->once())
            ->method('validate')
            ->willReturn($violationList);

        $violationList->expects($this->once())->method('count')->willReturn(0);

        $handler = new UserHandler($this->validator, $this->userRepository);

        $handler->create(new UserDTO('', ''));
    }

    public function testCreateFailed()
    {
        $violationList = $this->createMock(ConstraintViolationListInterface::class);
        $this->validator->expects($this->once())
            ->method('validate')
            ->willReturn($violationList);

        $violationList->expects($this->once())->method('count')->willReturn(1);

        $this->expectException(ValidationException::class);

        $handler = new UserHandler($this->validator, $this->userRepository);

        $handler->create(new UserDTO('', ''));
    }

    private function mockFindActiveRepoMethod(int $paramId, null|MockObject $expectedResult): void
    {
        $this->userRepository
            ->expects($this->once())
            ->method('findActiveById')
            ->with($paramId)
            ->willReturn($expectedResult);
    }
}
