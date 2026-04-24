<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullArgumentRule;

final class ClassWithNullArgumentInStaticCall
{
    public function run(): string
    {
        return StaticCallTarget::accept(null);
    }
}
