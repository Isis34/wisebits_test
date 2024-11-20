<?php

declare(strict_types=1);

namespace App\Validator;

use App\Service\EmailReliabilityCheckerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EmailReliabilityValidator extends ConstraintValidator
{
    public function __construct(
        readonly private EmailReliabilityCheckerInterface $reliabilityChecker
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        // should be caught with other validators
        if (! is_string($value)) {
            return;
        }

        $isValid = $this->reliabilityChecker->isEmailDomainReliable($value);
        if (! $isValid) {
            $this->context->buildViolation('Email domain is not reliable')
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();
        }
    }
}
