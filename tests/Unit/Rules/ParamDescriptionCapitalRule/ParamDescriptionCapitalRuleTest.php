<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ParamDescriptionCapitalRule;

use Haspadar\PHPStanRules\Rules\ParamDescriptionCapitalRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ParamDescriptionCapitalRule> */
final class ParamDescriptionCapitalRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ParamDescriptionCapitalRule();
    }

    #[Test]
    public function passesWhenParamDescriptionStartsWithCapital(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParamDescriptionCapitalRule/MethodWithParamDescription.php'],
            [],
            'Capital letter in @param description should pass',
        );
    }

    #[Test]
    public function reportsErrorWhenParamDescriptionStartsWithLowercase(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParamDescriptionCapitalRule/MethodWithLowercaseParam.php'],
            [
                ['@param $name description for getName() must start with a capital letter.', 16],
            ],
            '@param description starting with lowercase must be reported',
        );
    }

    #[Test]
    public function passesWhenParamHasNoDescription(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParamDescriptionCapitalRule/MethodWithoutParamDescription.php'],
            [],
            '@param without description should pass',
        );
    }

    #[Test]
    public function passesWhenMethodHasNoPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParamDescriptionCapitalRule/MethodWithNoPhpDoc.php'],
            [],
            'Method without PHPDoc should pass',
        );
    }

    #[Test]
    public function passesWhenPhpDocHasNoParamTag(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParamDescriptionCapitalRule/MethodWithPhpDocButNoParamTag.php'],
            [],
            'PHPDoc without @param tag should pass',
        );
    }

    #[Test]
    public function passesWhenMethodIsInInterface(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParamDescriptionCapitalRule/InterfaceMethod.php'],
            [],
            'Interface method should be skipped',
        );
    }

    #[Test]
    public function passesWhenMethodIsInTrait(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParamDescriptionCapitalRule/TraitMethod.php'],
            [],
            'Trait method should be skipped',
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParamDescriptionCapitalRule/SuppressedMethod.php'],
            [],
            'Suppressed error should pass',
        );
    }

    #[Test]
    public function reportsErrorsForTwoLowercaseParams(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParamDescriptionCapitalRule/MethodWithTwoLowercaseParams.php'],
            [
                ['@param $name description for getName() must start with a capital letter.', 17],
                ['@param $age description for getName() must start with a capital letter.', 17],
            ],
            'Both lowercase @param descriptions must be reported',
        );
    }

    #[Test]
    public function reportsErrorOnlyForLowercaseParamAmongMultiple(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParamDescriptionCapitalRule/MethodWithMultipleParams.php'],
            [
                ['@param $age description for getName() must start with a capital letter.', 17],
            ],
            'Only the lowercase @param description must be reported',
        );
    }
}
