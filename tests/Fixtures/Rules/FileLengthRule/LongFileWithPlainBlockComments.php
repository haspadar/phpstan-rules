<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\FileLengthRule;

final class LongFileWithPlainBlockComments
{
    /*
    This class processes data
    and returns results
    based on input values
    using simple arithmetic
    */
    public function run(): string
    {
        $a = 1;
        $b = 2;
        return (string) ($a + $b);
    }
}
