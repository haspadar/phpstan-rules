<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\StringLiteralsConcatenationRule;

final class ClassWithChainedConcat
{
    public function message(string $name): string
    {
        return "Hello, " . $name . "!";
    }
}
