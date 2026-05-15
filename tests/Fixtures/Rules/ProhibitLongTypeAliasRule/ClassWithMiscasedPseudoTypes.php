<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitLongTypeAliasRule;

final class ClassWithMiscasedPseudoTypes
{
    /**
     * Processes a value.
     *
     * @param SCALAR $a Input value.
     * @param MIXED $b Mixed value.
     * @param RESOURCE $c Resource handle.
     * @return void
     */
    public function process(mixed $a, mixed $b, mixed $c): void
    {
    }
}
