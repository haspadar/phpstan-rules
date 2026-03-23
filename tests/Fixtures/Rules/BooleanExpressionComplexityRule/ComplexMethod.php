<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\BooleanExpressionComplexityRule;

final class ComplexMethod
{
    public function run(bool $a, bool $b, bool $c, bool $d, bool $e, bool $f): bool
    {
        $x = $a && $b;

        return $x || $c && $d || $e && $f;
    }
}
