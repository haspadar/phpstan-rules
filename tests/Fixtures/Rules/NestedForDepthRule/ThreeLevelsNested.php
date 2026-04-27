<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedForDepthRule;

final class ThreeLevelsNested
{
    public function dive(array $cube): array
    {
        $result = [];
        foreach ($cube as $matrix) {
            foreach ($matrix as $row) {
                foreach ($row as $cell) {
                    foreach ($cell as $value) {
                        $result[] = $value;
                    }
                }
            }
        }

        return $result;
    }
}
