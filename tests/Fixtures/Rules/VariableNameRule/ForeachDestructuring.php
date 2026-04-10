<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\VariableNameRule;

final class ForeachDestructuring
{
    /**
     * @param list<array{int, string}> $rows
     */
    public function run(array $rows): void
    {
        foreach ($rows as [$x, $name]) {
            echo $x . $name;
        }
    }
}
