<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitLongTypeAliasRule;

final class ClassWithAllowedPseudoTypes
{
    /**
     * Processes a value.
     *
     * @param Scalar $a User-defined class.
     * @param Mixed $b User-defined class.
     * @param Resource $c User-defined class.
     * @return void
     */
    public function process(object $a, object $b, object $c): void
    {
    }
}
