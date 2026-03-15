<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MethodLinesRule;

final class SuppressedLongMethod
{
    /** @phpstan-ignore haspadar.methodLines */
    public function run(): string
    {
        $a = 1;
        $b = 2;
        $c = $a + $b;
        $d = $c * 2;
        $e = $d - 1;
        $f = $e + 3;
        $g = $f * 4;
        $h = $g - 5;
        $i = $h + 6;
        $j = $i * 7;
        $k = $j - 8;
        $l = $k + 9;
        $m = $l - 10;
        $n = $m + 11;
        $o = $n * 12;
        $p = $o - 13;
        $q = $p + 14;
        $r = $q * 15;

        return (string) $r;
    }
}
