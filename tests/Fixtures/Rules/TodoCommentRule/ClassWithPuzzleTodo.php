<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\TodoCommentRule;

final class ClassWithPuzzleTodo
{
    public function execute(): void
    {
        // @todo #42 refactor this method after the upstream fix lands
        $value = 1;
    }
}
