<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedForDepthRule;

final class SiblingLoopsWithNested
{
    public function combine(array $left, array $right): array
    {
        $result = [];
        foreach ($left as $row) {
            foreach ($row as $cell) {
                $result[] = $cell;
            }
        }
        foreach ($right as $row) {
            foreach ($row as $cell) {
                $result[] = $cell;
            }
        }

        return $result;
    }
}
