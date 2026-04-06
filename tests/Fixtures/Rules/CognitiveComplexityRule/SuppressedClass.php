<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CognitiveComplexityRule;

final class SuppressedClass
{
    /** @phpstan-ignore haspadar.cognitiveComplexity */
    public function complex(bool $a, bool $b): string
    {
        if ($a) {       // +1 (depth 0)
            if ($b) {   // +2 (depth 1)
            }
        } else {        // +1
        }

        return 'x';     // total = 4, exceeds limit 3 but suppressed
    }
}
