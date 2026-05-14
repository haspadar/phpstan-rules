<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitLongTypeAliasRule;

final class ClassWithLongTypeInVar
{
    /** @var integer */
    private int $value = 0;

    /**
     * Returns the value.
     *
     * @return int Value.
     */
    public function value(): int
    {
        return $this->value;
    }
}
