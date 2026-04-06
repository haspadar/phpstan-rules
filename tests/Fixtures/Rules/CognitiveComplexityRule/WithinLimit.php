<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CognitiveComplexityRule;

final class WithinLimit
{
    public function simple(bool $a, bool $b): string
    {
        if ($a) {       // +1
            return 'a';
        }

        if ($b) {       // +1
            return 'b';
        }

        return 'c';
    }
}
