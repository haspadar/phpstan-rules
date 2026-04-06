<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CognitiveComplexityRule;

final class ExactLimit
{
    public function atLimit(bool $a, bool $b): string
    {
        if ($a) {       // +1 (depth 0)
            if ($b) {   // +1 +1 (depth 1) = +2
            }
        }

        return 'x';     // total = 3
    }
}
