<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\TodoCommentRule;

final class ClassWithXxxComment
{
    public function execute(): void
    {
        // XXX temporary workaround
        $value = 1;
    }
}
