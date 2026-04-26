<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedIfDepthRule;

final class IfInsideClosure
{
    public function run(int $value): callable
    {
        if ($value > 0) {
            return function (int $other): int {
                if ($other > 0) {
                    if ($other < 100) {
                        return $other * 2;
                    }
                }

                return 0;
            };
        }

        return static fn (): int => 0;
    }
}
