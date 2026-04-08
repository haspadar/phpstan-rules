<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoInlineCommentRule;

final class MethodWithMultipleComments
{
    public function run(): string
    {
        // first comment
        $a = 'hello';
        // second comment
        $b = 'world';
        return $a . $b;
    }
}
