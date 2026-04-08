<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoInlineCommentRule;

final class MethodWithHashComment
{
    public function run(): string
    {
        # this is a hash comment
        $a = 'hello';
        return $a;
    }
}
