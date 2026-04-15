<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverAcceptNullArgumentsRule;

final class MethodWithUnionNull
{
    public function greet(string|null $name): string
    {
        return $name ?? 'world';
    }
}
