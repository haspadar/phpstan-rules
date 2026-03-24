<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\StatementCountRule;

final class MethodWithScopeBoundaryFirst
{
    public function run(): int
    {
        $fn = static function (): void {};
        $a = 1;
        $b = 2;
        $c = 3;
        $d = 4;
        unset($fn);
        return $a + $b + $c + $d;
    }
}
