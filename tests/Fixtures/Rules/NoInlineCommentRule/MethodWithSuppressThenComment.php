<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoInlineCommentRule;

final class MethodWithSuppressThenComment
{
    public function run(): string
    {
        // @infection-ignore-all
        // this is a regular comment
        $a = 'hello';
        return $a;
    }
}
