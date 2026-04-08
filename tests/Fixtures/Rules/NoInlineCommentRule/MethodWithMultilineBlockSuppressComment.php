<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoInlineCommentRule;

final class MethodWithMultilineBlockSuppressComment
{
    public function run(): string
    {
        /*
         * @var string $a
         */
        $a = 'hello';
        return $a;
    }
}
