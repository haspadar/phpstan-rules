<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ModifiedControlVariableRule;

final class ClassWithForNoInitAssign
{
    /** @param list<int> $items */
    public function process(array $items): void
    {
        $i = 0;

        for (; $i < count($items);) {
            echo $items[$i];
            $i++;
        }
    }
}
