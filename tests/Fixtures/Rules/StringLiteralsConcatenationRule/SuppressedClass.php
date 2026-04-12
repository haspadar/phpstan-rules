<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\StringLiteralsConcatenationRule;

final class SuppressedClass
{
    public function message(): string
    {
        return "Hello" . " world"; // @phpstan-ignore haspadar.stringConcat
    }
}
