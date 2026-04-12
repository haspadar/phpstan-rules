<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\StringLiteralsConcatenationRule;

final class ClassWithSprintfUsage
{
    public function greet(string $name): string
    {
        return sprintf('Hello, %s', $name);
    }
}
