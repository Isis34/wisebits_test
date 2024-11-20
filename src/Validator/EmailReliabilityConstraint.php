<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

final class EmailReliabilityConstraint extends Constraint
{
    public function validatedBy(): string
    {
        return EmailReliabilityValidator::class;
    }
}
