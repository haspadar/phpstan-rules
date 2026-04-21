<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\LackOfCohesionRule;

use Haspadar\PHPStanRules\Rules\LackOfCohesionRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<LackOfCohesionRule> */
final class LackOfCohesionRuleInterfaceAndTraitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new LackOfCohesionRule(1, ['minMethods' => 4, 'minProperties' => 2]);
    }

    #[Test]
    public function skipsInterfacesAndTraitsEvenWhenMethodsAreDisjoint(): void
    {
        $this->analyse(
            [
                __DIR__ . '/../../../Fixtures/Rules/LackOfCohesionRule/DisjointInterface.php',
                __DIR__ . '/../../../Fixtures/Rules/LackOfCohesionRule/DisjointTrait.php',
            ],
            [],
            'the rule targets Class_ nodes only — interfaces and traits must be skipped',
        );
    }
}
