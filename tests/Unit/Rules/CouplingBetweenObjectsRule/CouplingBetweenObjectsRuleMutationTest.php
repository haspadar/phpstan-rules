<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\CouplingBetweenObjectsRule;

use Haspadar\PHPStanRules\Rules\CouplingBetweenObjectsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<CouplingBetweenObjectsRule> */
final class CouplingBetweenObjectsRuleMutationTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new CouplingBetweenObjectsRule(0);
    }

    #[Test]
    public function countsBothProperties(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CouplingBetweenObjectsRule/ClassWithTwoProperties.php'],
            [
                ['Class ClassWithTwoProperties has a coupling between objects value of 2. Maximum allowed is 0.', 7],
            ],
        );
    }

    #[Test]
    public function countsReturnTypeNotPresentInParameters(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CouplingBetweenObjectsRule/ClassWithUniqueReturnType.php'],
            [
                ['Class ClassWithUniqueReturnType has a coupling between objects value of 2. Maximum allowed is 0.', 7],
            ],
        );
    }

    #[Test]
    public function deduplicatesRepeatedDependency(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CouplingBetweenObjectsRule/ClassWithRepeatedDependency.php'],
            [
                ['Class ClassWithRepeatedDependency has a coupling between objects value of 1. Maximum allowed is 0.', 7],
            ],
        );
    }

    #[Test]
    public function countsEachCatchTypeIndependently(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CouplingBetweenObjectsRule/ClassWithMultipleCatches.php'],
            [
                ['Class ClassWithMultipleCatches has a coupling between objects value of 3. Maximum allowed is 0.', 7],
            ],
        );
    }

    #[Test]
    public function countsIntersectionTypeOnly(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CouplingBetweenObjectsRule/ClassWithIntersectionTypeOnly.php'],
            [
                ['Class ClassWithIntersectionTypeOnly has a coupling between objects value of 2. Maximum allowed is 0.', 7],
            ],
        );
    }

    #[Test]
    public function countsUnionTypeOnly(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CouplingBetweenObjectsRule/ClassWithUnionTypeOnly.php'],
            [
                ['Class ClassWithUnionTypeOnly has a coupling between objects value of 2. Maximum allowed is 0.', 7],
            ],
        );
    }
}
