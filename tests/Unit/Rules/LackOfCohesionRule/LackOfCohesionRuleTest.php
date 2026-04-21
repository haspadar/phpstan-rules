<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\LackOfCohesionRule;

use Haspadar\PHPStanRules\Rules\LackOfCohesionRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<LackOfCohesionRule> */
final class LackOfCohesionRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new LackOfCohesionRule(1, ['minMethods' => 4, 'minProperties' => 2]);
    }

    #[Test]
    public function reportsErrorWhenClassSplitsIntoDisjointGroups(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/LackOfCohesionRule/DisjointClass.php'],
            [
                ['Class DisjointClass splits into 2 disjoint method groups (LCOM4). Maximum allowed is 1.', 7],
            ],
            'rule must fire with LCOM4 value and max when a class splits into disjoint method groups',
        );
    }
}
