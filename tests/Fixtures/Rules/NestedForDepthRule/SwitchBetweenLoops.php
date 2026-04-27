<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedForDepthRule;

final class SwitchBetweenLoops
{
    public function process(array $rows): array
    {
        $result = [];
        foreach ($rows as $row) {
            switch (true) {
                case is_array($row):
                    foreach ($row as $cell) {
                        $result[] = $cell;
                    }
                    break;
                default:
                    $result[] = $row;
            }
        }

        return $result;
    }
}
