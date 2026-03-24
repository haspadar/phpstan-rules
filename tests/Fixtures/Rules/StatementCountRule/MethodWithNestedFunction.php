<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\StatementCountRule;

final class MethodWithNestedFunction
{
    public function run(): int
    {
        function helper(): int
        {
            $a = 1;
            $b = 2;
            $c = 3;
            $d = 4;
            $e = 5;
            return $e;
        }
        return helper();
    }
}
