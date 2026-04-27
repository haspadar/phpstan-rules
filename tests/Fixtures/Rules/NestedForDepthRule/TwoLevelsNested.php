<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedForDepthRule;

final class TwoLevelsNested
{
    public function flatten(array $rows): array
    {
        $result = [];
        foreach ($rows as $row) {
            foreach ($row as $cell) {
                foreach ($cell as $value) {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }
}
