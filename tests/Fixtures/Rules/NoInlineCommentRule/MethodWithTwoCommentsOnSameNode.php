<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoInlineCommentRule;

final class MethodWithTwoCommentsOnSameNode
{
    public function run(): string
    {
        // first comment
        // second comment
        $a = 'hello';
        return $a;
    }
}
