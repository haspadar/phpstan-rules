<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoParameterReassignmentRule;

final class ClassWithParameterReassignment
{
    public function greet(string $name): string
    {
        $name = strtolower($name);

        return 'Hello, ' . $name;
    }
}
