<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MethodLinesRule;

final class ShortMethod
{
    public function run(): string
    {
        $a = 1;
        $b = 2;
        $c = $a + $b;
        $d = $c * 2;
        $e = $d - 1;

        return (string) $e;
    }
}
