<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ParameterNameRule;

final class ArrowFunctionParameter
{
    public function run(): int
    {
        $handler = static fn(int $x): int => $x + 1;

        return $handler(1);
    }
}
