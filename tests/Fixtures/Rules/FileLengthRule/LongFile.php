<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\FileLengthRule;

final class LongFile
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

        return (string) $i;
    }
}
