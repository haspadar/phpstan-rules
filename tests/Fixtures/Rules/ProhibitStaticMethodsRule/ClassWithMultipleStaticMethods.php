<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitPublicStaticMethodsRule;

final class ClassWithMultiplePublicStaticMethods
{
    public static function create(): self
    {
        return new self();
    }

    public static function empty(): self
    {
        return new self();
    }
}
