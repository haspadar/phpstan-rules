<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\TodoCommentRule;

final class ClassWithTodoComment
{
    public function execute(): void
    {
        // TODO: refactor this method
        $value = 1;
    }
}
