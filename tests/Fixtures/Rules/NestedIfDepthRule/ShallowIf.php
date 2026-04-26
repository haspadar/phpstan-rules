<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedIfDepthRule;

final class ShallowIf
{
    public function run(int $value): int
    {
        if ($value > 0) {
            return $value;
        }

        return 0;
    }
}
