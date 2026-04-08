<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoInlineCommentRule;

final class MethodWithBlockComment
{
    public function run(): string
    {
        /* this is a block comment */
        $a = 'hello';
        return $a;
    }
}
