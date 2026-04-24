<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\HiddenFieldRule;

final class NestedClosureShadow
{
    private int $total = 0;

    public function build(): \Closure
    {
        return function (): int {
            $total = 42;

            return $total;
        };
    }
}
