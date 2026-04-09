<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\AbbreviationAsWordInNameRule;

use Haspadar\PHPStanRules\Rules\AbbreviationAsWordInNameRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<AbbreviationAsWordInNameRule> */
final class AbbreviationAsWordInNameRuleAllowedTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new AbbreviationAsWordInNameRule(2, ['allowedAbbreviations' => ['JSON']]);
    }

    #[Test]
    public function passesWhenAbbreviationIsInAllowedList(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AbbreviationAsWordInNameRule/AllowedAbbreviationClass.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenAllowedAbbreviationFollowedByCamelCase(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AbbreviationAsWordInNameRule/AllowedAbbreviationCamelCase.php'],
            [],
        );
    }
}
