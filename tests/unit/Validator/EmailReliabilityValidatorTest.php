<?php

declare(strict_types=1);

namespace App\Tests\unit\Validator;

use App\Service\EmailReliabilityCheckerInterface;
use App\Validator\EmailReliabilityValidator;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class EmailReliabilityValidatorTest extends AbstractValidatorTest
{
    private $emailReliabilityService;

    protected function setUp(): void
    {
        $this->emailReliabilityService = $this->createMock(EmailReliabilityCheckerInterface::class);
        parent::setUp();
    }

    public function testValidationSucceed(): void
    {
        $this->emailReliabilityService->expects($this->once())->method('isEmailDomainReliable')->willReturn(true);
        $this->context->expects($this->never())->method('buildViolation');

        $validator = new EmailReliabilityValidator($this->emailReliabilityService);
        $validator->initialize($this->context);

        $validator->validate('mail@mail.com', $this->constraint);
    }

    public function testValidationFailed(): void
    {
        $this->emailReliabilityService->expects($this->once())->method('isEmailDomainReliable')->willReturn(false);

        $constraintBuilder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $this->context->expects($this->once())->method('buildViolation')->willReturn($constraintBuilder);
        $constraintBuilder->expects($this->once())->method('setParameter')->willReturn($constraintBuilder);
        $constraintBuilder->expects($this->once())->method('addViolation');

        $validator = new EmailReliabilityValidator($this->emailReliabilityService);
        $validator->initialize($this->context);

        $validator->validate('mail@mail.com', $this->constraint);
    }

    public function testValidationNotExecuted(): void
    {
        $this->emailReliabilityService->expects($this->never())->method('isEmailDomainReliable');
        $this->context->expects($this->never())->method('buildViolation');

        $validator = new EmailReliabilityValidator($this->emailReliabilityService);
        $validator->initialize($this->context);

        $validator->validate([], $this->constraint);
    }
}
