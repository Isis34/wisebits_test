<?php

namespace App\Service;

interface EmailReliabilityCheckerInterface
{
    public function isEmailDomainReliable(string $email): bool;
}
