<?php

declare(strict_types=1);

namespace App\Service;

class EmailReliabilityCheckerStub implements EmailReliabilityCheckerInterface
{
    /**
     * @TODO implement
     */
    public function isEmailDomainReliable(string $email): bool
    {
        return true;
    }
}
