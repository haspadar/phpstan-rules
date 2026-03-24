<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\StatementCountRule;

final class MethodWithTwoNestedFunctions
{
    public function run(): int
    {
        function helperA(): int
        {
            return 1;
        }
        function helperB(): int
        {
            return 2;
        }
        return helperA() + helperB();
    }
}
