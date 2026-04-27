<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedForDepthRule;

final class MixedLoopTypes
{
    public function chew(array $rows): int
    {
        $sum = 0;
        for ($i = 0; $i < count($rows); ++$i) {
            $j = 0;
            while ($j < count($rows[$i])) {
                foreach ($rows[$i][$j] as $cell) {
                    $k = 0;
                    do {
                        $sum += $cell + $k;
                        ++$k;
                    } while ($k < 1);
                }
                ++$j;
            }
        }

        return $sum;
    }
}
