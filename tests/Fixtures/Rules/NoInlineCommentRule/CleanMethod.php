<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoInlineCommentRule;

final class CleanMethod
{
    public function run(): string
    {
        $a = 'hello';
        $b = 'world';
        return $a . $b;
    }
}
