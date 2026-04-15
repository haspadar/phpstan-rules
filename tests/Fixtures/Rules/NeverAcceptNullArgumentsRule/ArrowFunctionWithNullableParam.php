<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverAcceptNullArgumentsRule;

final class ArrowFunctionWithNullableParam
{
    public function run(): string
    {
        $fn = static fn(?string $name): string => $name ?? 'world';

        return $fn('hello');
    }
}
