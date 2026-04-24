<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullArgumentRule;

final class ClassWithNullArgumentInAnonymousClassConstructor
{
    public function run(): object
    {
        return new class (null) {
            public function __construct(public ?string $value)
            {
            }
        };
    }
}
