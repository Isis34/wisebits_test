<?php

declare(strict_types=1);

namespace App\Service;

interface NameCheckerServiceInterface
{
    public function isForbidden(string $name): bool;
}
