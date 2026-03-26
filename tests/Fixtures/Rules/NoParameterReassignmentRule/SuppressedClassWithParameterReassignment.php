<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoParameterReassignmentRule;

final class SuppressedClassWithParameterReassignment
{
    public function greet(string $name): string
    {
        /** @phpstan-ignore haspadar.noParameterReassignment */
        $name = strtolower($name);

        return 'Hello, ' . $name;
    }
}
