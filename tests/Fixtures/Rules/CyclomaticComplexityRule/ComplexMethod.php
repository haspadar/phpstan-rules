<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CyclomaticComplexityRule;

final class ComplexMethod
{
    public function run(int $x, int $y): string
    {
        if ($x > 0) {
            if ($y > 0) {
                return 'both positive';
            }

            return 'x positive';
        }

        if ($y > 0) {
            return 'y positive';
        }

        return 'none';
    }
}
