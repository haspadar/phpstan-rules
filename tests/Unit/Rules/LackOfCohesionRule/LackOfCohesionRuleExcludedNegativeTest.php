<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\LackOfCohesionRule;

use Haspadar\PHPStanRules\Rules\LackOfCohesionRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<LackOfCohesionRule> */
final class LackOfCohesionRuleExcludedNegativeTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new LackOfCohesionRule(1, [
            'minMethods' => 4,
            'minProperties' => 2,
            'excludedClasses' => [],
        ]);
    }

    #[Test]
    public function reportsExcludedFixtureWhenExcludedClassesEmpty(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/LackOfCohesionRule/ExcludedClass.php'],
            [
                ['Class ExcludedClass splits into 2 disjoint method groups (LCOM4). Maximum allowed is 1.', 7],
            ],
            'without excludedClasses the same fixture must fire — proving the skip in the companion test is caused by excludedClasses, not by other thresholds',
        );
    }
}
