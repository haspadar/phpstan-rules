<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedForDepthRule;

final class SuppressedNested
{
    public function flatten(array $rows): array
    {
        $result = [];
        foreach ($rows as $row) {
            foreach ($row as $cell) {
                /** @phpstan-ignore haspadar.nestedForDepth */
                foreach ($cell as $value) {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }
}
