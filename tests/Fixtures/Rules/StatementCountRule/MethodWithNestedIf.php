<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\StatementCountRule;

final class MethodWithNestedIf
{
    public function run(bool $a, bool $b): int
    {
        if ($a) {
            if ($b) {
                return 1;
            }
            return 2;
        }
        return 3;
    }
}
