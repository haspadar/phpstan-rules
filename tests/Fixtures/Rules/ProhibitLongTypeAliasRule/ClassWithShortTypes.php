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

    /**
     * Processes a value.
     *
     * @param scalar $a Scalar value.
     * @param mixed $b Mixed value.
     * @param resource $c Resource handle.
     * @return void
     */
    public function process(mixed $a, mixed $b, mixed $c): void
    {
    }
}
