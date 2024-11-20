<?php

declare(strict_types=1);

namespace App\Tests\unit\Validator;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

abstract class AbstractValidatorTest extends TestCase
{
    protected $constraint;

    protected $context;

    protected function setUp(): void
    {
        $this->constraint = $this->createMock(Constraint::class);
        $this->context = $this->createMock(ExecutionContextInterface::class);
        parent::setUp();
    }
}
