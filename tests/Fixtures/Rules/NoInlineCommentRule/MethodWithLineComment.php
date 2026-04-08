<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoInlineCommentRule;

final class MethodWithLineComment
{
    public function run(): string
    {
        // this is a comment
        $a = 'hello';
        return $a;
    }
}
