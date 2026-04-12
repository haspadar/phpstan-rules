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
    protected function getRule(): Rule
    {
        return new TodoCommentRule();
    }

    #[Test]
    public function reportsErrorWhenTodoCommentFound(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithTodoComment.php'],
            [
                ['TODO comment found on line 11. Resolve the issue or create a ticket instead.', 11],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenFixmeCommentFound(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithFixmeComment.php'],
            [
                ['TODO comment found on line 11. Resolve the issue or create a ticket instead.', 11],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenXxxCommentFound(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithXxxComment.php'],
            [
                ['TODO comment found on line 11. Resolve the issue or create a ticket instead.', 11],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenTodoInPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithTodoInPhpDoc.php'],
            [
                ['TODO comment found on line 9. Resolve the issue or create a ticket instead.', 9],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenHashTodoFound(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithHashTodo.php'],
            [
                ['TODO comment found on line 11. Resolve the issue or create a ticket instead.', 11],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenBlockTodoFound(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithBlockTodo.php'],
            [
                ['TODO comment found on line 11. Resolve the issue or create a ticket instead.', 11],
            ],
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
