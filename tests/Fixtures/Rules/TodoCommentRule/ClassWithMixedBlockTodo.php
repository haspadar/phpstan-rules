<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\TodoCommentRule;

final class ClassWithMixedBlockTodo
{
    public function execute(): void
    {
        /*
         * @todo #77 drop legacy path after migration
         * FIXME rewrite this branch
         */
        $value = 1;
    }
}
