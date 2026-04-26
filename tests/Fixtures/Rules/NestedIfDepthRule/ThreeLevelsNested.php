<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedIfDepthRule;

final class ThreeLevelsNested
{
    public function run(int $a, int $b, int $c, int $d): int
    {
        if ($a > 0) {
            if ($b > 0) {
                if ($c > 0) {
                    if ($d > 0) {
                        return $a + $b + $c + $d;
                    }
                }
            }
        }

        return 0;
    }
}
