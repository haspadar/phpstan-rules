<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoInlineCommentRule;

final class MethodWithPhpDocAbove
{
    /** Returns a greeting string. */
    public function run(): string
    {
        $a = 'hello';
        return $a;
    }
}
