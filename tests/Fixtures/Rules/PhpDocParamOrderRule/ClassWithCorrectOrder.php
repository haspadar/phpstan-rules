<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocParamOrderRule;

final class ClassWithCorrectOrder
{
    /**
     * Adds two numbers.
     *
     * @param int $a First operand.
     * @param int $b Second operand.
     */
    public function add(int $a, int $b): int
    {
        return $a + $b;
    }
}
