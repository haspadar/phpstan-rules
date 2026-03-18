<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\FileLengthRule;

final class LongFileWithInlineSlashComment
{
    // a comment
    public function run(): int
    {
        $a = 1;
        return $a;
    }
}
