<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\TodoCommentRule;

final class ClassWithExtraMarkerTodo
{
    public function execute(): void
    {
        // FIXME @todo #42 figure out the right branch
        $value = 1;
    }
}
