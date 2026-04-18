<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\TodoCommentRule;

use Haspadar\PHPStanRules\Rules\TodoCommentRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<TodoCommentRule> */
final class TodoCommentRuleCustomFormatTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new TodoCommentRule(['issueFormat' => '/\bTODO\s+JIRA-\d+\b/']);
    }

    #[Test]
    public function passesWhenJiraFormatMatchesCustomPattern(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithJiraTodo.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenPuzzleFormatUsedWithJiraPattern(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithPuzzleTodo.php'],
            [[
                'Unresolved TODO comment on line 11. Use a comment matching /\bTODO\s+JIRA-\d+\b/ linked to an issue.',
                11,
            ]],
        );
    }
}
