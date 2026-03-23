<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\BooleanExpressionComplexityRule;

final class MethodWithArrowFunction
{
    public function run(): \Closure
    {
        return fn(bool $a, bool $b, bool $c, bool $d): bool => $a && $b || $c && $d;
    }
}
