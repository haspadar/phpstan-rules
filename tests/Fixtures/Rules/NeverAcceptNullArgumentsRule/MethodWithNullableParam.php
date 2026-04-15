<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverAcceptNullArgumentsRule;

final class MethodWithNullableParam
{
    public function greet(?string $name): string
    {
        return $name ?? 'world';
    }
}
