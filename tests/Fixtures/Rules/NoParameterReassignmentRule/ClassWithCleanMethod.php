<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoParameterReassignmentRule;

final class ClassWithCleanMethod
{
    public function greet(string $name): string
    {
        return 'Hello, ' . $name;
    }
}
