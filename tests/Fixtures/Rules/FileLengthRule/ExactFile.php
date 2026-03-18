<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\FileLengthRule;

final class ExactFile
{
    public function run(): int
    {
        $a = 1;
        $b = 2;
        return $a + $b;
    }
}
