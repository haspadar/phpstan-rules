<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\IfThenThrowElseRule;

final class ElseAfterThrow
{
    public function run(int $value): string
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('negative');
        } else {
            return 'ok';
        }
    }
}
