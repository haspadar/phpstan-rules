<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CyclomaticComplexityRule;

final class ExactMethod
{
    public function run(int $x, int $y): string
    {
        if ($x > 0) {
            return 'x positive';
        }

        if ($y > 0) {
            return 'y positive';
        }

        return 'none';
    }
}
