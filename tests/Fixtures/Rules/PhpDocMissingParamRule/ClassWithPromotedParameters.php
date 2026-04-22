<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocMissingParamRule;

final class ClassWithPromotedParameters
{
    /**
     * Builds a user.
     *
     * @param string $name Display name.
     */
    public function __construct(public string $name, public int $age)
    {
    }
}
