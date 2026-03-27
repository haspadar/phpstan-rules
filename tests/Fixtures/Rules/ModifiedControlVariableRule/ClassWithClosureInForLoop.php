<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ModifiedControlVariableRule;

final class ClassWithClosureInForLoop
{
    /** @return list<\Closure> */
    public function process(): array
    {
        $result = [];

        for ($i = 0; $i < 5; $i++) {
            $result[] = function () use ($i): int {
                $i = 0;

                return $i;
            };
        }

        return $result;
    }
}
