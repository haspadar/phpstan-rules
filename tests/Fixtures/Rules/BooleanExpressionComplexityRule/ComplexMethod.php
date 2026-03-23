<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\BooleanExpressionComplexityRule;

final class ComplexMethod
{
    public function run(bool $a, bool $b, bool $c, bool $d, bool $e): bool
    {
        return $a && $b || $c && $d || $e;
    }
}
