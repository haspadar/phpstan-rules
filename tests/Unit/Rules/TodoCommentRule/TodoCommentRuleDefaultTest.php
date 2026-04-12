<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\TodoCommentRule;

use Haspadar\PHPStanRules\Rules\TodoCommentRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<TodoCommentRule> */
final class TodoCommentRuleDefaultTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new TodoCommentRule();
    }

    #[Test]
    public function reportsErrorWithDefaultKeywords(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithTodoComment.php'],
            [
                ['TODO comment found on line 11. Resolve the issue or create a ticket instead.', 11],
            ],
        );
    }

    #[Test]
    public function passesWhenNoTodoComments(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithCleanComments.php'],
            [],
        );
    }
}
