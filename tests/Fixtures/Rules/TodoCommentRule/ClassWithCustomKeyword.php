<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\TodoCommentRule;

final class ClassWithCustomKeyword
{
    public function execute(): void
    {
        // HACK: workaround for upstream bug
        $value = 1;
    }
}
