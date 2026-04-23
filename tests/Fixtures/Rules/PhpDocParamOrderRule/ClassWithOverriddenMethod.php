<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocParamOrderRule;

use Override;

class ParentForParamOrderOverride
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

final class ClassWithOverriddenMethod extends ParentForParamOrderOverride
{
    /**
     * Adds two numbers louder.
     *
     * @param int $b Second.
     * @param int $a First.
     */
    #[Override]
    public function add(int $a, int $b): int
    {
        return ($a + $b) * 2;
    }
}
