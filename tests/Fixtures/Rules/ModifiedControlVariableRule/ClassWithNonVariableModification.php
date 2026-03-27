<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ModifiedControlVariableRule;

final class ClassWithNonVariableModification
{
    /** @param list<int> $items */
    public function process(array $items): void
    {
        for ($i = 0; $i < count($items); $i++) {
            $items[$i]++;
        }
    }
}
