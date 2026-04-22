<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticPropertiesRule;

final class AnonymousClassWithStaticProperty
{
    public function make(): object
    {
        return new class {
            public static int $count = 0;
        };
    }
}
