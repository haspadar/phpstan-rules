<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitPublicStaticMethodsRule;

final class ClassWithPublicStaticMethod
{
    public static function create(): self
    {
        return new self();
    }
}
