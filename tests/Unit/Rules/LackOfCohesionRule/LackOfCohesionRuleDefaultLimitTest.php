<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\LackOfCohesionRule;

use Haspadar\PHPStanRules\Rules\LackOfCohesionRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<LackOfCohesionRule> */
final class LackOfCohesionRuleDefaultLimitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new LackOfCohesionRule();
    }

    #[Test]
    public function passesWhenClassIsCohesiveAtDefaultLimits(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/LackOfCohesionRule/ExactDefaultCohesiveClass.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenClassSplitsIntoDisjointGroupsAtDefaultLimits(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/LackOfCohesionRule/DisjointDefaultClass.php'],
            [
                ['Class DisjointDefaultClass splits into 2 disjoint method groups (LCOM4). Maximum allowed is 1.', 7],
            ],
        );
    }
}
