<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\PhpDocParamDescriptionRule;

use Haspadar\PHPStanRules\Rules\PhpDocParamDescriptionRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<PhpDocParamDescriptionRule> */
final class PhpDocParamDescriptionRuleTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new PhpDocParamDescriptionRule(['checkPublicOnly' => false, 'skipOverridden' => false]);
    }

    #[Test]
    public function passesWhenEveryParamHasDescription(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamDescriptionRule/ClassWithAllDescriptions.php'],
            [],
            'All @param tags carry a non-empty description, no error must be reported',
        );
    }

    #[Test]
    public function reportsEmptyParamDescription(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamDescriptionRule/ClassWithEmptyParamDescription.php'],
            [
                ['@param $name for greet() is missing a description.', 14],
            ],
            'A @param tag with only type and name and no text must be reported as missing description',
        );
    }

    #[Test]
    public function reportsEveryEmptyDescriptionIndependently(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamDescriptionRule/ClassWithMultipleEmptyDescriptions.php'],
            [
                ['@param $first for combine() is missing a description.', 16],
                ['@param $second for combine() is missing a description.', 16],
                ['@param $third for combine() is missing a description.', 16],
            ],
            'Each empty @param description must produce its own error with the parameter name',
        );
    }

    #[Test]
    public function reportsOnlyTheParamWithoutDescription(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamDescriptionRule/ClassWithMixedDescriptions.php'],
            [
                ['@param $second for combine() is missing a description.', 16],
            ],
            'Only the @param tag without text must be flagged; siblings with descriptions must stay silent',
        );
    }

    #[Test]
    public function passesWhenMethodHasNoPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamDescriptionRule/ClassWithNoPhpDoc.php'],
            [],
            'Absent PHPDoc is the concern of PhpDocMissingMethodRule, not this rule',
        );
    }

    #[Test]
    public function reportsEmptyDescriptionOnOverrideWhenOptionDisabled(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamDescriptionRule/ClassWithOverriddenMethod.php'],
            [
                ['@param $name for greet() is missing a description.', 29],
            ],
            'When skipOverridden=false, #[Override] methods must also require non-empty @param descriptions',
        );
    }

    #[Test]
    public function reportsEmptyDescriptionInPrivateMethodWhenOptionDisabled(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamDescriptionRule/ClassWithPrivateMethod.php'],
            [
                ['@param $input for normalise() is missing a description.', 14],
            ],
            'When checkPublicOnly=false, private methods must also require non-empty @param descriptions',
        );
    }

    #[Test]
    public function passesWhenErrorIsSuppressed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamDescriptionRule/SuppressedEmptyDescription.php'],
            [],
            'A @phpstan-ignore haspadar.phpdocParamDescription comment must silence the error',
        );
    }
}
