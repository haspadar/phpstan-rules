<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ModifiedControlVariableRule;

final class ClassWithForeachKeyModification
{
    /** @param array<int, string> $items */
    public function process(array $items): void
    {
        foreach ($items as $key => $value) {
            $key++;
            echo $value;
        }
    }
}
