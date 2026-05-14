<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitLongTypeAliasRule;

final class ClassWithUppercaseAlias
{
    /**
     * Checks a value.
     *
     * @param INTEGER $n Value.
     * @return int Result.
     */
    public function check(int $n): int
    {
        return $n;
    }
}
