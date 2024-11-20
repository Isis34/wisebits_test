<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends Exception
{
    public function __construct(
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null,
        private ?ConstraintViolationListInterface $violationList = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getDetails(): array
    {
        if (! $this->violationList) {
            return [];
        }

        $errors = [];
        foreach ($this->violationList as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $errors;
    }
}
