<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\TodoCommentRule;

final class ClassWithMixedCaseTodo
{
    public function execute(): void
    {
        // ToDo refactor this later
        $value = 1;
    }
}
