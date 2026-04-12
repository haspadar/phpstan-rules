<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\TodoCommentRule;

final class ClassWithHashTodo
{
    public function execute(): void
    {
        # TODO fix this
        $value = 1;
    }
}
