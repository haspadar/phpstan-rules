<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\VariableNameRule;

final class ForeachVariable
{
    /**
     * @param list<string> $items
     */
    public function run(array $items): void
    {
        foreach ($items as $k => $v) {
            echo $k . $v;
        }
    }
}
