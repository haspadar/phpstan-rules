<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitLongTypeAliasRule;

final class ClassWithMultipleLongTypeParams
{
    /**
     * Merges two values.
     *
     * @param integer $a First.
     * @param integer $b Second.
     * @return int Result.
     */
    public function merge(int $a, int $b): int
    {
        return $a + $b;
    }
}
