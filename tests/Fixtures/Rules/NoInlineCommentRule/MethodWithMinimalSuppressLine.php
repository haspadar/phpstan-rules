<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoInlineCommentRule;

final class MethodWithMinimalSuppressLine
{
    public function run(): string
    {
        //@x
        $a = 'hello';
        return $a;
    }
}
