<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\StringLiteralsConcatenationRule;

final class ClassWithLiteralConcat
{
    public function message(): string
    {
        return "Hello" . " world";
    }
}
