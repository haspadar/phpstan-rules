<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\TodoCommentRule;

final class SuppressedClass
{
    public function execute(): void
    {
        $value = 1; // TODO: suppressed @phpstan-ignore haspadar.todoComment
    }
}
