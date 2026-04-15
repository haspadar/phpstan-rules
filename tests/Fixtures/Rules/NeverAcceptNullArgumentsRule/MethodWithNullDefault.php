<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverAcceptNullArgumentsRule;

final class MethodWithNullDefault
{
    public function greet(string $name = null): string
    {
        return $name ?? 'world';
    }
}
