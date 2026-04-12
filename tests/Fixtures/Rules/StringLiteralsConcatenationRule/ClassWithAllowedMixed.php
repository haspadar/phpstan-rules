<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\StringLiteralsConcatenationRule;

final class ClassWithAllowedMixed
{
    public function greet(string $name): string
    {
        return "Hello, " . $name;
    }
}
