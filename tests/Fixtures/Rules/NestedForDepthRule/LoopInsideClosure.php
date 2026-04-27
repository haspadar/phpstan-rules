<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedForDepthRule;

final class LoopInsideClosure
{
    public function build(array $rows): callable
    {
        foreach ($rows as $row) {
            return function (array $items) use ($row): array {
                $result = [];
                foreach ($items as $item) {
                    foreach ($row as $cell) {
                        $result[] = [$item, $cell];
                    }
                }

                return $result;
            };
        }

        return static fn (): array => [];
    }
}
