<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitLongTypeAliasRule;

final class ClassWithLongTypeInReturn
{
    /**
     * Checks a flag.
     *
     * @return boolean Result.
     */
    public function check(): bool
    {
        return true;
    }
}
