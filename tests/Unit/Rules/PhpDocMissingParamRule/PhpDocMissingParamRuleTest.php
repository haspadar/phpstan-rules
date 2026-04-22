<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\PhpDocMissingParamRule;

use Haspadar\PHPStanRules\Rules\PhpDocMissingParamRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<PhpDocMissingParamRule> */
final class PhpDocMissingParamRuleTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new PhpDocMissingParamRule(['checkPublicOnly' => false, 'skipOverridden' => false]);
    }

    #[Test]
    public function passesWhenEveryParameterIsDocumented(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingParamRule/ClassWithAllParamsDocumented.php'],
            [],
            'A PHPDoc block that documents every parameter must not produce any error',
        );
    }

    #[Test]
    public function reportsMissingParamTag(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingParamRule/ClassWithMissingParamTag.php'],
            [
                ['PHPDoc for greet() is missing @param for parameter $name.', 12],
            ],
            'A method with a PHPDoc block but no @param for $name must be reported once',
        );
    }

    #[Test]
    public function reportsEveryMissingParameterIndependently(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingParamRule/ClassWithMultipleMissingParams.php'],
            [
                ['PHPDoc for combine() is missing @param for parameter $second.', 14],
                ['PHPDoc for combine() is missing @param for parameter $third.', 14],
            ],
            'Each undocumented parameter must produce its own error with the parameter name',
        );
    }

    #[Test]
    public function passesWhenMethodHasNoPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingParamRule/ClassWithNoPhpDoc.php'],
            [],
            'Absent PHPDoc is the concern of PhpDocMissingMethodRule, not this rule',
        );
    }

    #[Test]
    public function passesWhenMethodHasNoParameters(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingParamRule/ClassWithNoParameters.php'],
            [],
            'A method with a PHPDoc block but no parameters must not trigger the rule',
        );
    }

    #[Test]
    public function reportsMissingParamForPromotedParameters(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingParamRule/ClassWithPromotedParameters.php'],
            [
                ['PHPDoc for __construct() is missing @param for parameter $age.', 14],
            ],
            'Constructor property promotion parameters must be checked like ordinary parameters',
        );
    }

    #[Test]
    public function reportsMissingParamEvenOnOverrideWhenOptionDisabled(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingParamRule/ClassWithOverriddenMethod.php'],
            [
                ['PHPDoc for greet() is missing @param for parameter $name.', 27],
            ],
            'When skipOverridden=false, #[Override] methods must also require complete @param coverage',
        );
    }

    #[Test]
    public function reportsMissingParamInPrivateMethodWhenOptionDisabled(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingParamRule/ClassWithPrivateMethod.php'],
            [
                ['PHPDoc for normalise() is missing @param for parameter $input.', 12],
            ],
            'When checkPublicOnly=false, private methods with PHPDoc must also require @param tags',
        );
    }

    #[Test]
    public function passesWhenErrorIsSuppressed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingParamRule/SuppressedMissingParam.php'],
            [],
            'A @phpstan-ignore haspadar.phpdocMissingParam comment must silence the error',
        );
    }
}
