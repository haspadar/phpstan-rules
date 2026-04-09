<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\AbbreviationAsWordInNameRule;

use Haspadar\PHPStanRules\Rules\AbbreviationAsWordInNameRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<AbbreviationAsWordInNameRule> */
final class AbbreviationAsWordInNameRuleDefaultLimitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new AbbreviationAsWordInNameRule();
    }

    #[Test]
    public function passesWhenAbbreviationFitsDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AbbreviationAsWordInNameRule/ExactLimitClass.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenAbbreviationExceedsDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AbbreviationAsWordInNameRule/ClassWithLongAbbreviation.php'],
            [
                ["Abbreviation in name 'HTTPSClient' must contain no more than 4 consecutive capital letters.", 7],
            ],
        );
    }
}
