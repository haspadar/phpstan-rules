<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\StatementCountRule;

final class SuppressedMethod
{
    /** @phpstan-ignore haspadar.executableStatements */
    public function run(bool $a): int
    {
        $b = !$a;
        $c = $b ? 1 : 2;
        $d = $c + 1;
        $e = $d * 2;
        $f = $e - 1;
        return $f;
    }
}
