<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitLongTypeAliasRule;

final class ClassWithLongTypeInThrows
{
    /**
     * Divides two numbers.
     *
     * @param int $a Dividend.
     * @param int $b Divisor.
     * @return float Result.
     * @throws integer When division fails.
     */
    public function divide(int $a, int $b): float
    {
        return $a / $b;
    }
}
