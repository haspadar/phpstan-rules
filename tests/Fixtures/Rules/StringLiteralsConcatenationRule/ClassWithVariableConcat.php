<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\StringLiteralsConcatenationRule;

final class ClassWithVariableConcat
{
    public function combine(string $first, string $last): string
    {
        return $first . $last;
    }
}
