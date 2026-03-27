<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ModifiedControlVariableRule;

final class ClassWithEmptyForInit
{
    /** @param list<int> $items */
    public function process(array $items): void
    {
        $i = 0;

        for (; $i < count($items); $i++) {
            echo $items[$i];
        }
    }
}
