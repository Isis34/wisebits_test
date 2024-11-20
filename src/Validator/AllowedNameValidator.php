<?php

declare(strict_types=1);

namespace App\Validator;

use App\Service\NameCheckerServiceInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AllowedNameValidator extends ConstraintValidator
{
    public function __construct(
        readonly private NameCheckerServiceInterface $forbiddenNameService
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        // should be caught with other validators
        if (! is_string($value)) {
            return;
        }

        if ($this->forbiddenNameService->isForbidden($value)) {
            $this->context->buildViolation('Name is included in the forbidden list')
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();
        }
    }
}
