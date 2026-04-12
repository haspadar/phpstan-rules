<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\StringLiteralsConcatenationRule;

final class ClassWithRightNestedConcat
{
    /**
     * Parenthesized right-hand concat to exercise the right-nested branch.
     *
     * @param non-empty-string $name
     */
    public function greet(string $name): string
    {
        return $name . ("!" . "!");
    }
}
