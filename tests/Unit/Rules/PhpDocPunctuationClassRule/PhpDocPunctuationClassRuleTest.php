<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\PhpDocPunctuationClassRule;

use Haspadar\PHPStanRules\Rules\PhpDocPunctuationClassRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<PhpDocPunctuationClassRule> */
final class PhpDocPunctuationClassRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new PhpDocPunctuationClassRule();
    }

    #[Test]
    public function passesWhenSummaryEndsWithPeriod(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocPunctuationClassRule/ClassWithValidSummary.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenSummaryLacksPunctuation(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocPunctuationClassRule/ClassWithInvalidSummary.php'],
            [
                ['PHPDoc summary for ClassWithInvalidSummary must end with a period, question mark, or exclamation mark.', 8],
            ],
        );
    }

    #[Test]
    public function passesWhenClassHasNoPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocPunctuationClassRule/ClassWithNoPhpDoc.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenPhpDocHasTagsOnly(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocPunctuationClassRule/ClassWithTagsOnly.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenSummaryEndsWithQuestionMark(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocPunctuationClassRule/ClassWithQuestionMark.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenSummaryEndsWithExclamation(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocPunctuationClassRule/ClassWithExclamation.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenPhpDocIsEmpty(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocPunctuationClassRule/ClassWithEmptyPhpDoc.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocPunctuationClassRule/SuppressedClass.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenSummaryStartsWithLowercase(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocPunctuationClassRule/ClassWithLowercaseSummary.php'],
            [
                ['PHPDoc summary for ClassWithLowercaseSummary must start with a capital letter.', 8],
            ],
        );
    }
}
