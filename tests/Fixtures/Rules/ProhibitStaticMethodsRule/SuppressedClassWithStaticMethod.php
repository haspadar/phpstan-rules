<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitPublicStaticMethodsRule;

final class SuppressedClassWithPublicStaticMethod
{
    /** @phpstan-ignore haspadar.noPublicStatic */
    public static function create(): self
    {
        return new self();
    }
}
