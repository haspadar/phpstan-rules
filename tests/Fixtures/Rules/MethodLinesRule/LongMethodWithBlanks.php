<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MethodLinesRule;

final class LongMethodWithBlanks
{
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

        return (string) $o;
    }
}
