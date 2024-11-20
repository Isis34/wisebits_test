<?php

declare(strict_types=1);

namespace App\Service;

class NameCheckerCheckerServiceStub implements NameCheckerServiceInterface
{
    public function isForbidden(string $name): bool
    {
        return false;
    }
}
