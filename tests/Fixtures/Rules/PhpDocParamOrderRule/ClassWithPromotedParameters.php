<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocParamOrderRule;

final class ClassWithPromotedParameters
{
    /**
     * Builds a user.
     *
     * @param int    $age  Age in years.
     * @param string $name Display name.
     */
    public function __construct(public string $name, public int $age)
    {
    }
}
