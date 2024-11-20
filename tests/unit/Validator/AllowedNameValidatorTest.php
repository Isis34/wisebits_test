<?php

declare(strict_types=1);

namespace App\Tests\unit\Validator;

use App\Service\NameCheckerServiceInterface;
use App\Validator\AllowedNameValidator;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class AllowedNameValidatorTest extends AbstractValidatorTest
{
    private $nameChecker;

    protected function setUp(): void
    {
        $this->nameChecker = $this->createMock(NameCheckerServiceInterface::class);
        parent::setUp();
    }

    public function testValidationSucceed(): void
    {
        $this->nameChecker->expects($this->once())->method('isForbidden')->willReturn(false);
        $this->context->expects($this->never())->method('buildViolation');

        $validator = new AllowedNameValidator($this->nameChecker);
        $validator->initialize($this->context);

        $validator->validate('mail@mail.com', $this->constraint);
    }

    public function testValidationFailed(): void
    {
        $this->nameChecker->expects($this->once())->method('isForbidden')->willReturn(true);

        $constraintBuilder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $this->context->expects($this->once())->method('buildViolation')->willReturn($constraintBuilder);
        $constraintBuilder->expects($this->once())->method('setParameter')->willReturn($constraintBuilder);
        $constraintBuilder->expects($this->once())->method('addViolation');

        $validator = new AllowedNameValidator($this->nameChecker);
        $validator->initialize($this->context);

        $validator->validate('mail@mail.com', $this->constraint);
    }

    public function testValidationNotExecuted(): void
    {
        $this->nameChecker->expects($this->never())->method('isForbidden');
        $this->context->expects($this->never())->method('buildViolation');

        $validator = new AllowedNameValidator($this->nameChecker);
        $validator->initialize($this->context);

        $validator->validate([], $this->constraint);
    }
}
