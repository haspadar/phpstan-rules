<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CognitiveComplexityRule;

final class ExceedsDefaultLimit
{
    public function complex(bool $a, bool $b, bool $c, bool $d): string
    {
        if ($a) {               // +1 (depth 0)
            if ($b) {           // +2 (depth 1)
                if ($c) {       // +3 (depth 2)
                    if ($d) {   // +4 (depth 3)
                    }
                }
            }
        } else {                // +1
            if ($b) {           // +2 (depth 1)
            }
        }

        return 'x';             // total = 1+2+3+4+1+2 = 13
    }
}
