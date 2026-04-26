<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MultipleVariableDeclarationsRule;

use RuntimeException;

final class MultipleStatementsInLoops
{
    public function run(int $limit, array $items): int
    {
        $sum = 0;

        for ($i = 0; $i < $limit; ++$i) {
            $a = 1; $b = 2;
            $sum += $a + $b;
        }

        foreach ($items as $item) {
            $c = 1; $d = 2;
            $sum += $c + $d;
        }

        while ($sum < 100) {
            $e = 1; $f = 2;
            $sum += $e + $f;
        }

        do {
            $g = 1; $h = 2;
            $sum += $g + $h;
        } while ($sum < 200);

        try {
            $i = 1; $j = 2;
            $sum += $i + $j;
        } catch (RuntimeException $exception) {
            $k = 1; $m = 2;
            $sum += $k + $m;
        } finally {
            $n = 1; $o = 2;
            $sum += $n + $o;
        }

        try {
            $r = 1; $s = 2;
            $sum += $r + $s;
        } finally {
            $t = 1; $u = 2;
            $sum += $t + $u;
        }

        $closure = function (): int {
            $p = 1; $q = 2;

            return $p + $q;
        };

        return $sum + $closure();
    }
}
