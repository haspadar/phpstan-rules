<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitLongTypeAliasRule;

final class ClassWithDoubleAndReal
{
    /**
     * Computes ratio.
     *
     * @param double $a First.
     * @param real $b Second.
     * @return float Result.
     */
    public function ratio(float $a, float $b): float
    {
        return $a / $b;
    }
}
