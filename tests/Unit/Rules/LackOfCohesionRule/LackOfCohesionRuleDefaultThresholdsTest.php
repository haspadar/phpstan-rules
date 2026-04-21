<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\LackOfCohesionRule;

use Haspadar\PHPStanRules\Rules\LackOfCohesionRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<LackOfCohesionRule> */
final class LackOfCohesionRuleDefaultThresholdsTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new LackOfCohesionRule();
    }

    #[Test]
    public function usesDefaultMinThresholdsOfSevenMethodsAndThreeProperties(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/LackOfCohesionRule/DefaultThresholdsDisjoint.php'],
            [
                ['Class DefaultThresholdsDisjoint splits into 2 disjoint method groups (LCOM4). Maximum allowed is 1.', 7],
            ],
            'without options the rule must apply minMethods=7 and minProperties=3 as defaults',
        );
    }
}
