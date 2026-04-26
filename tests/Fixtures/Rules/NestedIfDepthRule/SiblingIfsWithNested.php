<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedIfDepthRule;

final class SiblingIfsWithNested
{
    public function run(int $a, int $b, int $c): int
    {
        if ($a > 0) {
            if ($b > 0) {
                $a += $b;
            }
        }

        if ($b > 0) {
            if ($c > 0) {
                $a += $c;
            }
        }

        return $a;
    }
}
