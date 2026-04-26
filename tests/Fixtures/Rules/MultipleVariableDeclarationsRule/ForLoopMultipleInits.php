<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MultipleVariableDeclarationsRule;

final class ForLoopMultipleInits
{
    public function run(int $n): int
    {
        $sum = 0;

        for ($i = 0, $j = $n; $i < $j; ++$i, --$j) {
            $sum += $i;
        }

        return $sum;
    }
}
