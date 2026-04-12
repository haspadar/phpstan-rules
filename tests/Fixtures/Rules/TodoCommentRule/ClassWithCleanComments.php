<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\TodoCommentRule;

final class ClassWithCleanComments
{
    public function execute(): string
    {
        $value = 'hello';

        return $value;
    }
}
