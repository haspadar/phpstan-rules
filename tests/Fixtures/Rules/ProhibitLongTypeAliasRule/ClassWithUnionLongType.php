<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitLongTypeAliasRule;

final class ClassWithUnionLongType
{
    /**
     * Formats a value.
     *
     * @param integer|string $value Input.
     * @return string Result.
     */
    public function format(int|string $value): string
    {
        return (string) $value;
    }
}
