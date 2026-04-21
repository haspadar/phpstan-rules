<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\LackOfCohesionRule;

use Haspadar\PHPStanRules\Rules\LackOfCohesionRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<LackOfCohesionRule> */
final class LackOfCohesionRuleExternalReferencesTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new LackOfCohesionRule(1, ['minMethods' => 4, 'minProperties' => 2]);
    }

    #[Test]
    public function ignoresPropertyFetchesAndCallsOnOtherObjects(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/LackOfCohesionRule/ExternalReferences.php'],
            [
                ['Class ExternalReferences splits into 2 disjoint method groups (LCOM4). Maximum allowed is 1.', 7],
            ],
            'accesses to other objects and external static calls must not contribute to the graph',
        );
    }
}
