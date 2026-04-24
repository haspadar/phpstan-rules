<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullArgumentRule;

function userDefinedGreet(?string $name): string
{
    return $name ?? 'unknown';
}

final class ClassWithNullArgumentInFunctionCall
{
    public function run(): string
    {
        return userDefinedGreet(null);
    }
}
