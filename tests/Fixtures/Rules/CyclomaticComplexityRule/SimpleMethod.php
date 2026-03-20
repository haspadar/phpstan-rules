<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CyclomaticComplexityRule;

final class SimpleMethod
{
    public function run(int $x): string
    {
        if ($x > 0) {
            return 'positive';
        }

        return 'other';
    }
}
