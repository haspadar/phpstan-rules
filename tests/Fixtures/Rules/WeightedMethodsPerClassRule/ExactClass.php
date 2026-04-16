<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\WeightedMethodsPerClassRule;

final class ExactClass
{
    public function one(int $val): int
    {
        if ($val > 0) {
            return $val;
        }

        return 0;
    }

    public function two(int $val): int
    {
        if ($val > 0) {
            return $val;
        }

        return 0;
    }

    public function three(): void
    {
    }
}
