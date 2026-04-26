<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MultipleVariableDeclarationsRule;

final class MultipleStatementsPerLine
{
    public function run(int $value): int
    {
        $a = 1; $b = 2;

        if ($value > 0) {
            $c = 1; $d = 2;

            return $a + $b + $c + $d;
        }

        switch ($value) {
            case 0:
                $e = 1; $f = 2;

                return $a + $b + $e + $f;
            case 1:
                $g = 1; $h = 2;

                return $a + $b + $g + $h;
        }

        return $a + $b;
    }

    public function tally(int $count): int
    {
        $sum = 0; $delta = 1;

        return $sum + $count + $delta;
    }
}
