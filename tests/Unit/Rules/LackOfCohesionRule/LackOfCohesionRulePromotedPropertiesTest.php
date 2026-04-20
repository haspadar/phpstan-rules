<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\LackOfCohesionRule;

use Haspadar\PHPStanRules\Rules\LackOfCohesionRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<LackOfCohesionRule> */
final class LackOfCohesionRulePromotedPropertiesTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new LackOfCohesionRule();
    }

    #[Test]
    public function countsPromotedConstructorParametersTowardsMinProperties(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/LackOfCohesionRule/PromotedPropertiesDisjointClass.php'],
            [
                ['Class PromotedPropertiesDisjointClass splits into 2 disjoint method groups (LCOM4). Maximum allowed is 1.', 7],
            ],
            'promoted constructor properties count toward minProperties, otherwise the class would be skipped',
        );
    }
}
