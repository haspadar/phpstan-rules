<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\TodoCommentRule;

final class ClassWithMultilinePuzzleTodo
{
    public function execute(): void
    {
        // @todo #91 this branch still fails under heavy load,
        // investigate the session store before the next release.
        $value = 1;
    }
}
