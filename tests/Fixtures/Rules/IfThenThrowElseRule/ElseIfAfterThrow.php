<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\IfThenThrowElseRule;

final class ElseIfAfterThrow
{
    public function run(int $value): string
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('negative');
        } elseif ($value === 0) {
            return 'zero';
        }

        return 'positive';
    }
}
