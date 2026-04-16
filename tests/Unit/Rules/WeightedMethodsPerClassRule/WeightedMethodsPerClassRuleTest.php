<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\WeightedMethodsPerClassRule;

use Haspadar\PHPStanRules\Rules\WeightedMethodsPerClassRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<WeightedMethodsPerClassRule> */
final class WeightedMethodsPerClassRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new WeightedMethodsPerClassRule(5);
    }

    #[Test]
    public function passesWhenWmcIsWithinLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/WeightedMethodsPerClassRule/SimpleClass.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenWmcExceedsLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/WeightedMethodsPerClassRule/ComplexClass.php'],
            [
                ['Class ComplexClass has weighted method complexity of 6. Maximum allowed is 5.', 7],
            ],
        );
    }

    #[Test]
    public function passesWhenWmcIsExactlyAtLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/WeightedMethodsPerClassRule/ExactClass.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenAllBranchTypesExceedLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/WeightedMethodsPerClassRule/AllBranchTypesClass.php'],
            [
                ['Class AllBranchTypesClass has weighted method complexity of 23. Maximum allowed is 5.', 7],
            ],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/WeightedMethodsPerClassRule/SuppressedComplexClass.php'],
            [],
        );
    }
}
