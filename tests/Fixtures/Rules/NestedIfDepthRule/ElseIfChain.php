<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedIfDepthRule;

final class ElseIfChain
{
    public function run(int $value): string
    {
        if ($value === 0) {
            return 'zero';
        } elseif ($value === 1) {
            return 'one';
        } elseif ($value === 2) {
            return 'two';
        } else {
            return 'other';
        }
    }
}
