<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ModifiedControlVariableRule;

final class ClassWithForeachModification
{
    /** @param list<string> $items */
    public function process(array $items): void
    {
        foreach ($items as $item) {
            $item = trim($item);
            echo $item;
        }
    }
}
