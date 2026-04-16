<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\WeightedMethodsPerClassRule;

use Haspadar\PHPStanRules\Rules\WeightedMethodsPerClassRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<WeightedMethodsPerClassRule> */
final class WeightedMethodsPerClassRuleDefaultLimitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new WeightedMethodsPerClassRule();
    }

    #[Test]
    public function passesWhenWmcIsWithinDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/WeightedMethodsPerClassRule/ComplexClass.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenWmcExceedsDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/WeightedMethodsPerClassRule/LargeComplexClass.php'],
            [
                ['Class LargeComplexClass has weighted method complexity of 52. Maximum allowed is 50.', 7],
            ],
        );
    }
}
