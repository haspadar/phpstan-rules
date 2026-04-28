<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\IfThenThrowElseRule;

final class SuppressedClass
{
    public function run(int $value): string
    {
        /** @phpstan-ignore haspadar.ifThenThrowElse */
        if ($value < 0) {
            throw new \InvalidArgumentException('negative');
        } else {
            return 'ok';
        }
    }
}
