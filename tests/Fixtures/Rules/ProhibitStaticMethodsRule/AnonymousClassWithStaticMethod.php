<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticMethodsRule;

final class AnonymousClassWithStaticMethod
{
    public function make(): object
    {
        return new class {
            public static function create(): static
            {
                return new static();
            }
        };
    }
}
