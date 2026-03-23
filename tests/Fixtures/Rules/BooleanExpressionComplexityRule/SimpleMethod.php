<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\BooleanExpressionComplexityRule;

final class SimpleMethod
{
    public function run(bool $a, bool $b, bool $c): bool
    {
        return $a && $b || $c;
    }
}
