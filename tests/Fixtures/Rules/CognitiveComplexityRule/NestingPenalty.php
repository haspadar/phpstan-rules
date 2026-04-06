<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CognitiveComplexityRule;

final class NestingPenalty
{
    public function deeplyNested(bool $a, bool $b, bool $c): string
    {
        if ($a) {           // +1 (depth 0)
            if ($b) {       // +1 +1 (depth 1) = +2
                if ($c) {   // +1 +2 (depth 2) = +3
                }
            }
        }

        return 'x';         // total = 6
    }
}
