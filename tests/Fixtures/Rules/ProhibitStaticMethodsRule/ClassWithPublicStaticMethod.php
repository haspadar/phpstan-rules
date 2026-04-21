<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticMethodsRule;

final class ClassWithPublicStaticMethod
{
    public static function create(): self
    {
        return new self();
    }
}
