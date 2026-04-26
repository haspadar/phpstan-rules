<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedIfDepthRule;

final class IfAfterArrowFunction
{
    public function run(int $value): int
    {
        $double = static fn (int $x): int => $x * 2;

        if ($value > 0) {
            if ($value < 100) {
                if ($double($value) > 50) {
                    return $value;
                }
            }
        }

        return 0;
    }
}
