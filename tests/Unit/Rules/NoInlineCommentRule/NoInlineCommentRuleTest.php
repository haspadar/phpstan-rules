<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NoInlineCommentRule;

use Haspadar\PHPStanRules\Rules\NoInlineCommentRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<NoInlineCommentRule> */
final class NoInlineCommentRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new NoInlineCommentRule();
    }

    #[Test]
    public function passesWhenMethodHasNoComments(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoInlineCommentRule/CleanMethod.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenMethodHasLineComment(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoInlineCommentRule/MethodWithLineComment.php'],
            [
                ['Inline comment found on line 11; comments inside method bodies are forbidden.', 11],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenMethodHasHashComment(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoInlineCommentRule/MethodWithHashComment.php'],
            [
                ['Inline comment found on line 11; comments inside method bodies are forbidden.', 11],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenMethodHasBlockComment(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoInlineCommentRule/MethodWithBlockComment.php'],
            [
                ['Inline comment found on line 11; comments inside method bodies are forbidden.', 11],
            ],
        );
    }

    #[Test]
    public function passesWhenSuppressDirectivePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoInlineCommentRule/MethodWithSuppressComment.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenPhpDocAboveMethodOnly(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoInlineCommentRule/MethodWithPhpDocAbove.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoInlineCommentRule/SuppressedClass.php'],
            [],
        );
    }

    #[Test]
    public function reportsAllCommentsWhenMultiplePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoInlineCommentRule/MethodWithMultipleComments.php'],
            [
                ['Inline comment found on line 11; comments inside method bodies are forbidden.', 11],
                ['Inline comment found on line 13; comments inside method bodies are forbidden.', 13],
            ],
        );
    }

    #[Test]
    public function reportsBothCommentsOnSameNode(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoInlineCommentRule/MethodWithTwoCommentsOnSameNode.php'],
            [
                ['Inline comment found on line 11; comments inside method bodies are forbidden.', 11],
                ['Inline comment found on line 12; comments inside method bodies are forbidden.', 12],
            ],
        );
    }

    #[Test]
    public function reportsRegularCommentAfterSuppressDirective(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoInlineCommentRule/MethodWithSuppressThenComment.php'],
            [
                ['Inline comment found on line 12; comments inside method bodies are forbidden.', 12],
            ],
        );
    }

    #[Test]
    public function passesWhenMinimalSuppressLineComment(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoInlineCommentRule/MethodWithMinimalSuppressLine.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenHashSuppressComment(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoInlineCommentRule/MethodWithHashSuppressComment.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenBlockSuppressComment(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoInlineCommentRule/MethodWithBlockSuppressComment.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenTightBlockSuppressComment(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoInlineCommentRule/MethodWithTightBlockSuppressComment.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenMinimalBlockSuppressComment(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoInlineCommentRule/MethodWithMinimalBlockSuppressComment.php'],
            [],
        );
    }
}
