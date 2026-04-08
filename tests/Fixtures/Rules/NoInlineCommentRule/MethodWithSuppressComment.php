<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoInlineCommentRule;

final class MethodWithSuppressComment
{
    public function run(): string
    {
        // @infection-ignore-all
        $a = 'hello';
        # @psalm-suppress MixedAssignment
        $b = 'world';
        /* @var string $c */
        $c = $a . $b;
        return $c;
    }
}
