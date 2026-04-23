<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocParamOrderRule;

final class ClassWithSubsetInOrder
{
    /**
     * Sums three numbers.
     *
     * @param int $a First.
     * @param int $c Third.
     */
    public function sum(int $a, int $b, int $c): int
    {
        return $a + $b + $c;
    }
}
