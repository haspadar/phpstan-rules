<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\AbbreviationAsWordInNameRule;

use Haspadar\PHPStanRules\Rules\AbbreviationAsWordInNameRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<AbbreviationAsWordInNameRule> */
final class AbbreviationAsWordInNameRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new AbbreviationAsWordInNameRule(2);
    }

    #[Test]
    public function passesWhenNamesHaveShortAbbreviations(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AbbreviationAsWordInNameRule/CleanClass.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenClassNameHasLongAbbreviation(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AbbreviationAsWordInNameRule/ClassWithLongAbbreviation.php'],
            [
                ["Abbreviation in name 'HTTPSClient' must contain no more than 2 consecutive capital letters.", 7],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenMethodNameHasLongAbbreviation(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AbbreviationAsWordInNameRule/MethodWithLongAbbreviation.php'],
            [
                ["Abbreviation in name 'parseJSONAPI' must contain no more than 2 consecutive capital letters.", 9],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenPropertyNameHasLongAbbreviation(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AbbreviationAsWordInNameRule/PropertyWithLongAbbreviation.php'],
            [
                ["Abbreviation in name 'HTTPSConnection' must contain no more than 2 consecutive capital letters.", 9],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenParameterNameHasLongAbbreviation(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AbbreviationAsWordInNameRule/ParameterWithLongAbbreviation.php'],
            [
                ["Abbreviation in name 'XMLHTTPRequest' must contain no more than 2 consecutive capital letters.", 9],
            ],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AbbreviationAsWordInNameRule/SuppressedClass.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenConstantsUseUpperSnakeCase(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AbbreviationAsWordInNameRule/ConstantSkipped.php'],
            [],
        );
    }

    #[Test]
    public function reportsMethodAfterConstant(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AbbreviationAsWordInNameRule/ClassWithConstBeforeMethod.php'],
            [
                ["Abbreviation in name 'parseJSONAPI' must contain no more than 2 consecutive capital letters.", 11],
            ],
        );
    }

    #[Test]
    public function reportsMultipleViolations(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AbbreviationAsWordInNameRule/ClassWithMultipleViolations.php'],
            [
                ["Abbreviation in name 'HTTPSConnection' must contain no more than 2 consecutive capital letters.", 9],
                ["Abbreviation in name 'parseJSONAPI' must contain no more than 2 consecutive capital letters.", 11],
                ["Abbreviation in name 'XMLHTTPRequest' must contain no more than 2 consecutive capital letters.", 11],
            ],
        );
    }

    #[Test]
    public function reportsMultipleProperties(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AbbreviationAsWordInNameRule/ClassWithMultipleProperties.php'],
            [
                ["Abbreviation in name 'HTTPSFirst' must contain no more than 2 consecutive capital letters.", 9],
                ["Abbreviation in name 'HTTPSSecond' must contain no more than 2 consecutive capital letters.", 11],
            ],
        );
    }

    #[Test]
    public function reportsMethodAndParameterViolations(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AbbreviationAsWordInNameRule/MethodAndParameterViolation.php'],
            [
                ["Abbreviation in name 'parseJSONAPI' must contain no more than 2 consecutive capital letters.", 9],
                ["Abbreviation in name 'XMLHTTPRequest' must contain no more than 2 consecutive capital letters.", 9],
            ],
        );
    }

    #[Test]
    public function reportsFirstViolatingRunInNameWithMultipleCapitalGroups(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AbbreviationAsWordInNameRule/ClassWithMultipleCapitalRuns.php'],
            [
                ["Abbreviation in name 'loadHTTPSandParseXMLAPI' must contain no more than 2 consecutive capital letters.", 9],
            ],
        );
    }

    #[Test]
    public function passesWhenClassIsAnonymous(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AbbreviationAsWordInNameRule/AnonymousClass.php'],
            [],
        );
    }

    #[Test]
    public function reportsPromotedPropertyParameter(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AbbreviationAsWordInNameRule/PromotedProperty.php'],
            [
                ["Abbreviation in name 'HTTPSConnection' must contain no more than 2 consecutive capital letters.", 10],
            ],
        );
    }
}
