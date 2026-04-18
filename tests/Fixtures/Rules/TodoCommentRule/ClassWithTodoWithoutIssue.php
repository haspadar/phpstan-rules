<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\TodoCommentRule;

final class ClassWithTodoWithoutIssue
{
    public function execute(): void
    {
        // @todo refactor this method
        $value = 1;
    }
}
