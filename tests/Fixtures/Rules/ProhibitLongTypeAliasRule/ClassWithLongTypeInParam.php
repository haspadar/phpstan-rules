<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitLongTypeAliasRule;

final class ClassWithLongTypeInParam
{
    /**
     * Adds a number.
     *
     * @param integer $n Value.
     * @return int Result.
     */
    public function add(int $n): int
    {
        return $n + 1;
    }
}
