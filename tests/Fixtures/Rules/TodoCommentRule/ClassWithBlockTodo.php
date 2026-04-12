<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\TodoCommentRule;

final class ClassWithBlockTodo
{
    public function execute(): void
    {
        /* TODO: fix later */
        $value = 1;
    }
}
