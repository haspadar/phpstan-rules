<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocParamDescriptionRule;

final class ClassWithAllDescriptions
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
