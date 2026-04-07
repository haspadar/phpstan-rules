<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ClassLengthRule;

use Haspadar\PHPStanRules\Rules\ClassLengthRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ClassLengthRule> */
final class ClassLengthRuleSkipCommentsTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ClassLengthRule(10, ['skipComments' => true]);
    }

    #[Test]
    public function passesWhenCommentLinesSkipped(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ClassLengthRule/LongClassWithComments.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenBlockCommentLinesSkipped(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ClassLengthRule/LongClassWithBlockComment.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenMultilineBlockCommentSkipped(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ClassLengthRule/ClassWithMultilineBlockComment.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenInlineBlockCommentSkipped(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ClassLengthRule/ClassWithInlineBlockComment.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenCodeExceedsLimitDespitePlainBlockCommentSkipped(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ClassLengthRule/LongClassWithPlainBlockComment.php'],
            [
                ['Class LongClassWithPlainBlockComment is 13 lines long. Maximum allowed is 10.', 7],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenCodeExceedsLimitDespiteSlashCommentSkipped(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ClassLengthRule/LongClassWithSlashComment.php'],
            [
                ['Class LongClassWithSlashComment is 12 lines long. Maximum allowed is 10.', 7],
            ],
        );
    }
}
