<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\IfThenThrowElseRule;

final class ElseAfterReturn
{
    public function run(int $value): string
    {
        if ($value < 0) {
            return 'negative';
        } else {
            return 'non-negative';
        }
    }
}
