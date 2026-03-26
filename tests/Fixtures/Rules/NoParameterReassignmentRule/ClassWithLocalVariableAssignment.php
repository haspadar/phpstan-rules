<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoParameterReassignmentRule;

final class ClassWithLocalVariableAssignment
{
    public function greet(string $name): string
    {
        $normalized = strtolower($name);

        return 'Hello, ' . $normalized;
    }
}
