<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\TodoCommentRule;

use Haspadar\PHPStanRules\Rules\TodoCommentRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<TodoCommentRule> */
final class TodoCommentRuleCustomKeywordsTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new TodoCommentRule(['keywords' => ['HACK']]);
    }

    #[Test]
    public function reportsErrorWhenHackCommentFound(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithCustomKeyword.php'],
            [
                ['TODO comment found on line 11. Resolve the issue or create a ticket instead.', 11],
            ],
        );
    }

    #[Test]
    public function passesWhenTodoCommentNotInKeywords(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TodoCommentRule/ClassWithTodoComment.php'],
            [],
        );
    }
}
