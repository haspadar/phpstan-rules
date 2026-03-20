<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CyclomaticComplexityRule;

final class MethodWithMatch
{
    public function run(int $x): string
    {
        return match ($x) {
            1 => 'one',
            2 => 'two',
            default => 'other',
        };
    }
}
