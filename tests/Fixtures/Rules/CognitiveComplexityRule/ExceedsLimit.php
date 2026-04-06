<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CognitiveComplexityRule;

final class ExceedsLimit
{
    public function complex(bool $a, bool $b): string
    {
        if ($a) {       // +1 (depth 0)
            if ($b) {   // +1 +1 (depth 1) = +2
            }
        } else {        // +1
        }

        return 'x';     // total = 4
    }
}
