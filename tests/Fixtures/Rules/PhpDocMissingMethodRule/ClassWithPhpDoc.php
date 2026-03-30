<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocMissingMethodRule;

class ClassWithPhpDoc
{
    /** Returns a greeting. */
    public function greet(): string
    {
        return 'Hello';
    }

    /** Computes the sum. */
    public function sum(int $a, int $b): int
    {
        return $a + $b;
    }
}
