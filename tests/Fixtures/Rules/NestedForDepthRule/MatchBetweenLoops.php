<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedForDepthRule;

final class MatchBetweenLoops
{
    public function process(array $rows): array
    {
        $result = [];
        foreach ($rows as $row) {
            $kind = match (true) {
                is_array($row) => 'list',
                default => 'scalar',
            };
            foreach ((array) $row as $cell) {
                $result[] = [$kind, $cell];
            }
        }

        return $result;
    }
}
