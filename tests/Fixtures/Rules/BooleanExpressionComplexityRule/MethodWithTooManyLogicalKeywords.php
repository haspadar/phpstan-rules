<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\BooleanExpressionComplexityRule;

final class MethodWithTooManyLogicalKeywords
{
    public function run(bool $a, bool $b, bool $c, bool $d, bool $e): bool
    {
        return $a and $b or $c xor $d and $e;
    }
}
