<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverAcceptNullArgumentsRule;

final class MethodWithoutNullableParam
{
    public function greet(string $name): string
    {
        return $name;
    }
}
