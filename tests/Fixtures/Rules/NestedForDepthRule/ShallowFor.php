<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedForDepthRule;

final class ShallowFor
{
    public function run(array $items): int
    {
        $sum = 0;
        foreach ($items as $value) {
            $sum += $value;
        }

        return $sum;
    }
}
