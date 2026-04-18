<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\TodoCommentRule;

use Haspadar\PHPStanRules\Rules\TodoCommentRule;
use InvalidArgumentException;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<TodoCommentRule> */
final class TodoCommentRuleTest extends RuleTestCase
{
    private const string ERROR_TEMPLATE = "Unresolved TODO comment on line %d. Use '@todo #ISSUE description' format linked to an issue.";

    protected function getRule(): Rule
    {
        return new TodoCommentRule();
    }

    #[Test]
    public function reportsErrorWhenTodoCommentFound(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithTodoComment.php'],
            [[sprintf(self::ERROR_TEMPLATE, 11), 11]],
        );
    }

    #[Test]
    public function reportsErrorWhenFixmeCommentFound(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithFixmeComment.php'],
            [[sprintf(self::ERROR_TEMPLATE, 11), 11]],
        );
    }

    #[Test]
    public function reportsErrorWhenXxxCommentFound(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithXxxComment.php'],
            [[sprintf(self::ERROR_TEMPLATE, 11), 11]],
        );
    }

    #[Test]
    public function reportsErrorWhenTodoInPhpDocWithoutIssue(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithTodoInPhpDoc.php'],
            [[sprintf(self::ERROR_TEMPLATE, 9), 9]],
        );
    }

    #[Test]
    public function reportsErrorWhenHashTodoFound(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithHashTodo.php'],
            [[sprintf(self::ERROR_TEMPLATE, 11), 11]],
        );
    }

    #[Test]
    public function reportsErrorWhenBlockTodoFound(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithBlockTodo.php'],
            [[sprintf(self::ERROR_TEMPLATE, 11), 11]],
        );
    }

    #[Test]
    public function reportsErrorWhenTodoWithoutIssueNumber(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithTodoWithoutIssue.php'],
            [[sprintf(self::ERROR_TEMPLATE, 11), 11]],
        );
    }

    #[Test]
    public function reportsErrorWhenMarkerUsesMixedCase(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithMixedCaseTodo.php'],
            [[sprintf(self::ERROR_TEMPLATE, 11), 11]],
        );
    }

    #[Test]
    public function reportsErrorWhenBlockContainsMixedMarkerLines(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithMixedBlockTodo.php'],
            [[sprintf(self::ERROR_TEMPLATE, 11), 11]],
        );
    }

    #[Test]
    public function reportsErrorWhenIssueFormatCoexistsWithOtherMarker(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithExtraMarkerTodo.php'],
            [[sprintf(self::ERROR_TEMPLATE, 11), 11]],
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
    public function passesWhenMultilinePuzzleTodoIsLinked(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithMultilinePuzzleTodo.php'],
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

    #[Test]
    public function throwsWhenIssueFormatIsNotAValidRegex(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new TodoCommentRule(['issueFormat' => 'not-a-regex']);
    }
}
