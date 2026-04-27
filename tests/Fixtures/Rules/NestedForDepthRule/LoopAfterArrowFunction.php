<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedForDepthRule;

final class LoopAfterArrowFunction
{
    public function run(array $rows): array
    {
        $double = static fn (int $value): int => $value * 2;

        $result = [];
        foreach ($rows as $row) {
            foreach ($row as $cell) {
                foreach ($cell as $value) {
                    $result[] = $double($value);
                }
            }
        }

        return $result;
    }
}
