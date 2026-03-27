<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ModifiedControlVariableRule;

final class ClassWithOtherVarModifiedInForLoop
{
    /** @param list<int> $items */
    public function process(array $items): void
    {
        $sum = 0;

        for ($i = 0; $i < count($items); $i++) {
            $sum += $items[$i];
        }
    }
}
