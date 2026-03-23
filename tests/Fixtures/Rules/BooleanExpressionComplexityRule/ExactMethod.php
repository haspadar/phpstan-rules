<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\BooleanExpressionComplexityRule;

final class ExactMethod
{
    public function run(bool $a, bool $b, bool $c, bool $d): bool
    {
        return $a && $b || $c && $d;
    }
}
