<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\TodoCommentRule;

final class ClassWithFixmeComment
{
    public function execute(): void
    {
        // FIXME: broken edge case
        $value = 1;
    }
}
