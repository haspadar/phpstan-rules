<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocParamOrderRule;

final class ClassWithWrongOrder
{
    /**
     * Adds two numbers.
     *
     * @param int $b Second operand.
     * @param int $a First operand.
     */
    public function add(int $a, int $b): int
    {
        return $a + $b;
    }
}
