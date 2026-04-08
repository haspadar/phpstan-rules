<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoInlineCommentRule;

final class SuppressedClass
{
    public function run(): string
    {
        /** @phpstan-ignore-next-line haspadar.noInlineComment */
        // this comment is suppressed
        $a = 'hello';
        return $a;
    }
}
