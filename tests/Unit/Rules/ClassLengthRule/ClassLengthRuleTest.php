<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ClassLengthRule;

use Haspadar\PHPStanRules\Rules\ClassLengthRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ClassLengthRule> */
final class ClassLengthRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ClassLengthRule(10);
    }

    #[Test]
    public function passesWhenClassFitsWithinLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ClassLengthRule/ShortClass.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenClassExceedsLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ClassLengthRule/LongClass.php'],
            [
                ['Class LongClass is 12 lines long. Maximum allowed is 10.', 7],
            ],
        );
    }

    #[Test]
    public function passesWhenClassIsExactlyAtLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ClassLengthRule/ExactClass.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ClassLengthRule/SuppressedLongClass.php'],
            [],
        );
    }

    #[Test]
    public function countsBlankLinesWithDefaultOptions(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ClassLengthRule/LongClassWithBlanksNoSkip.php'],
            [
                ['Class LongClassWithBlanksNoSkip is 12 lines long. Maximum allowed is 10.', 7],
            ],
        );
    }

    #[Test]
    public function countsCommentLinesWithDefaultOptions(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ClassLengthRule/LongClassWithCommentsNoSkip.php'],
            [
                ['Class LongClassWithCommentsNoSkip is 11 lines long. Maximum allowed is 10.', 7],
            ],
        );
    }

    #[Test]
    public function countsBlockCommentLinesWithDefaultOptions(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ClassLengthRule/LongClassWithBlockComment.php'],
            [
                ['Class LongClassWithBlockComment is 11 lines long. Maximum allowed is 10.', 7],
            ],
        );
    }

    #[Test]
    public function passesWhenClassIsExactlyAtLimitWithTrailingLines(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ClassLengthRule/ExactClassWithTrailingLines.php'],
            [],
        );
    }
}
