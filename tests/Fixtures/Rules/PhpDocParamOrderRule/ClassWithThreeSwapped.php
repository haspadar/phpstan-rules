<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocParamOrderRule;

final class ClassWithThreeSwapped
{
    /**
     * Sums three numbers.
     *
     * @param int $c Third.
     * @param int $a First.
     * @param int $b Second.
     */
    public function sum(int $a, int $b, int $c): int
    {
        return $a + $b + $c;
    }
}
