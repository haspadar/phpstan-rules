<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitLongTypeAliasRule;

final class SuppressedLongTypeAlias
{
    /**
     * Adds a number.
     *
     * @param integer $n Value.
     * @return int Result.
     * @phpstan-ignore haspadar.prohibitLongTypeAlias
     */
    public function add(int $n): int
    {
        return $n + 1;
    }
}
