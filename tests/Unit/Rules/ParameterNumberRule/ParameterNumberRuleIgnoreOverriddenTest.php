<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ParameterNumberRule;

use Haspadar\PHPStanRules\Rules\ParameterNumberRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ParameterNumberRule> */
final class ParameterNumberRuleIgnoreOverriddenTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ParameterNumberRule(3, ['ignoreOverridden' => false]);
    }

    #[Test]
    public function reportsErrorForOverriddenMethodWhenIgnoreDisabled(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParameterNumberRule/ClassWithOverriddenMethod.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\ParameterNumberRule\ClassWithOverriddenMethod::create() has 4 parameters. Maximum allowed is 3.',
                    9,
                ],
            ],
        );
    }

    #[Test]
    public function reportsErrorForNonOverriddenLongMethod(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParameterNumberRule/ClassWithNonOverriddenLongMethod.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\ParameterNumberRule\ClassWithNonOverriddenLongMethod::create() has 4 parameters. Maximum allowed is 3.',
                    9,
                ],
            ],
        );
    }
}
