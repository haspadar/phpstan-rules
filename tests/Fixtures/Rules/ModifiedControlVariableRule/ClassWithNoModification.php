<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ModifiedControlVariableRule;

final class ClassWithNoModification
{
    /** @return list<int> */
    public function process(): array
    {
        $result = [];

        for ($i = 0; $i < 5; $i++) {
            $result[] = $i;
        }

        foreach ([1, 2, 3] as $item) {
            $result[] = $item;
        }

        return $result;
    }
}
