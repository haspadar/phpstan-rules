<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\StatementCountRule;

final class MethodWithArrowFunction
{
    public function run(): \Closure
    {
        return fn(): int => 1 + 2 + 3 + 4 + 5;
    }
}
