<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedForDepthRule;

final class IfBetweenLoops
{
    public function gather(array $rows): array
    {
        $result = [];
        foreach ($rows as $row) {
            if (is_array($row)) {
                foreach ($row as $cell) {
                    $result[] = $cell;
                }
            }
        }

        return $result;
    }
}
