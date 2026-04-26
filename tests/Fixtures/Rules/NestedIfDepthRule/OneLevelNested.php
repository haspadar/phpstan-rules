<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedIfDepthRule;

final class OneLevelNested
{
    public function run(int $a, int $b): int
    {
        if ($a > 0) {
            if ($b > 0) {
                return $a + $b;
            }
        }

        return 0;
    }
}
