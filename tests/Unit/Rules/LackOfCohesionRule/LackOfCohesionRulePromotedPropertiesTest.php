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
        return new LackOfCohesionRule(1, ['minMethods' => 4, 'minProperties' => 3]);
    }

    #[Test]
    public function countsPromotedConstructorParametersAsProperties(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/LackOfCohesionRule/PromotedProperties.php'],
            [
                ['Class PromotedProperties splits into 2 disjoint method groups (LCOM4). Maximum allowed is 1.', 7],
            ],
            'promoted constructor parameters must count toward minProperties so the rule still fires',
        );
    }
}
