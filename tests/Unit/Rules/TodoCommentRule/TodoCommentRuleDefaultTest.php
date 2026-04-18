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
    public function reportsErrorWhenMarkerPresentWithoutIssue(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithTodoComment.php'],
            [[
                "Unresolved TODO comment on line 11. Use '@todo #ISSUE description' format linked to an issue.",
                11,
            ]],
        );
    }

    #[Test]
    public function passesWhenPuzzleTodoLinksToIssue(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithPuzzleTodo.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenNoMarkersPresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithCleanComments.php'],
            [],
        );
    }
}
