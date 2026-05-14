<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitLongTypeAliasRule;

final class ClassWithShortTypes
{
    /**
     * Adds a number.
     *
     * @param int $n Value.
     * @return bool Result.
     */
    public function check(int $n): bool
    {
        return $n > 0;
    }
}
