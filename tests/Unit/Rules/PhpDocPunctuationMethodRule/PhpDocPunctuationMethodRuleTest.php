<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\PhpDocPunctuationMethodRule;

use Haspadar\PHPStanRules\Rules\PhpDocPunctuationMethodRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<PhpDocPunctuationMethodRule> */
final class PhpDocPunctuationMethodRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new PhpDocPunctuationMethodRule();
    }

    #[Test]
    public function passesWhenMethodSummaryEndsWithPeriod(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocPunctuationMethodRule/ClassWithValidMethodSummary.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenMethodSummaryLacksPunctuation(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocPunctuationMethodRule/ClassWithInvalidMethodSummary.php'],
            [
                ['PHPDoc summary for save() must end with a period, question mark, or exclamation mark.', 10],
            ],
        );
    }

    #[Test]
    public function passesWhenMethodHasNoPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocPunctuationMethodRule/ClassWithNoMethodPhpDoc.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenMethodPhpDocHasTagsOnly(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocPunctuationMethodRule/ClassWithMethodTagsOnly.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocPunctuationMethodRule/SuppressedMethod.php'],
            [],
        );
    }
}
