<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\BooleanExpressionComplexityRule;

final class MethodWithNestedScope
{
    public function run(): \Closure
    {
        return static function (bool $a, bool $b, bool $c, bool $d): bool {
            return $a && $b || $c && $d;
        };
    }
}
