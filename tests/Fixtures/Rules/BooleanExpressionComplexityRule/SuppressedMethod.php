<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\BooleanExpressionComplexityRule;

final class SuppressedMethod
{
    /** @phpstan-ignore haspadar.booleanComplexity */
    public function run(bool $a, bool $b, bool $c, bool $d, bool $e): bool
    {
        return $a && $b || $c && $d || $e;
    }
}
