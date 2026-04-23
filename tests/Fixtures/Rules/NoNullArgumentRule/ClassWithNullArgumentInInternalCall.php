<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullArgumentRule;

final class ClassWithNullArgumentInInternalCall
{
    public function run(): string
    {
        return str_replace('a', 'b', 'banana', null);
    }
}
