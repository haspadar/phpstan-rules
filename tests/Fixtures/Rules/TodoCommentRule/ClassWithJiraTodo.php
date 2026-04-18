<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\TodoCommentRule;

final class ClassWithJiraTodo
{
    public function execute(): void
    {
        // TODO JIRA-123 finish the integration
        $value = 1;
    }
}
