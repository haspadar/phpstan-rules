<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitLongTypeAliasRule;

final class ClassWithAllowedAlias
{
    /**
     * Wraps a value.
     *
     * @param Integer $n User-defined class.
     * @return Integer Result.
     */
    public function wrap(object $n): object
    {
        return $n;
    }
}
