<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\StatementCountRule;

final class MethodWithAnonymousClass
{
    public function run(): object
    {
        return new class () {
            public function inner(): void
            {
                $a = 1;
                $b = 2;
                $c = 3;
                $d = 4;
                $e = 5;
            }
        };
    }
}
