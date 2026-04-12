<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\TodoCommentRule;

final class ClassWithTodoInPhpDoc
{
    /** @todo implement validation */
    public function execute(): void
    {
        $value = 1;
    }
}
