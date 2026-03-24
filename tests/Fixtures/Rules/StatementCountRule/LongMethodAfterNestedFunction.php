<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\StatementCountRule;

final class LongMethodAfterNestedFunction
{
    public function run(): int
    {
        function innerHelper(): int
        {
            return 0;
        }
        $a = 1;
        $b = 2;
        $c = 3;
        $d = 4;
        $e = 5;
        return $a + $b + $c + $d + $e;
    }
}
