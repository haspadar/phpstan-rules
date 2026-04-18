<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\TodoCommentRule;

use Haspadar\PHPStanRules\Rules\TodoCommentRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<TodoCommentRule> */
final class TodoCommentRuleTest extends RuleTestCase
{
    private const string ERROR_LINE_11 = "Unresolved TODO comment on line 11. Use '@todo #ISSUE description' format linked to an issue.";
    private const string ERROR_LINE_9 = "Unresolved TODO comment on line 9. Use '@todo #ISSUE description' format linked to an issue.";

    protected function getRule(): Rule
    {
        return new TodoCommentRule();
    }

    #[Test]
    public function reportsErrorWhenTodoCommentFound(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithTodoComment.php'],
            [[self::ERROR_LINE_11, 11]],
        );
    }

    #[Test]
    public function reportsErrorWhenFixmeCommentFound(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithFixmeComment.php'],
            [[self::ERROR_LINE_11, 11]],
        );
    }

    #[Test]
    public function reportsErrorWhenXxxCommentFound(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithXxxComment.php'],
            [[self::ERROR_LINE_11, 11]],
        );
    }

    #[Test]
    public function reportsErrorWhenTodoInPhpDocWithoutIssue(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithTodoInPhpDoc.php'],
            [[self::ERROR_LINE_9, 9]],
        );
    }

    #[Test]
    public function reportsErrorWhenHashTodoFound(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithHashTodo.php'],
            [[self::ERROR_LINE_11, 11]],
        );
    }

    #[Test]
    public function reportsErrorWhenBlockTodoFound(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithBlockTodo.php'],
            [[self::ERROR_LINE_11, 11]],
        );
    }

    #[Test]
    public function reportsErrorWhenTodoWithoutIssueNumber(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithTodoWithoutIssue.php'],
            [[self::ERROR_LINE_11, 11]],
        );
    }

    #[Test]
    public function passesWhenTodoLinkedToIssue(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithPuzzleTodo.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenCommentsClean(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithCleanComments.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenMethodIsAbstract(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithAbstractMethod.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/SuppressedClass.php'],
            [],
        );
    }
}
