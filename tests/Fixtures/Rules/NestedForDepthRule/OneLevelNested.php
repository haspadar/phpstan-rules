<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedForDepthRule;

final class OneLevelNested
{
    public function pairs(array $xs, array $ys): array
    {
        $pairs = [];
        foreach ($xs as $x) {
            foreach ($ys as $y) {
                $pairs[] = [$x, $y];
            }
        }

        return $pairs;
    }
}
