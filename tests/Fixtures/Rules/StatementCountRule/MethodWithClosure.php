<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\StatementCountRule;

final class MethodWithClosure
{
    public function run(): \Closure
    {
        return static function (): void {
            $a = 1;
            $b = 2;
            $c = 3;
            $d = 4;
            $e = 5;
        };
    }
}
