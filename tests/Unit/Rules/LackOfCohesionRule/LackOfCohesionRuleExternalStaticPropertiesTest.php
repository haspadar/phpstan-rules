<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\LackOfCohesionRule;

use Haspadar\PHPStanRules\Rules\LackOfCohesionRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<LackOfCohesionRule> */
final class LackOfCohesionRuleExternalStaticPropertiesTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new LackOfCohesionRule(1, ['minMethods' => 4, 'minProperties' => 2]);
    }

    #[Test]
    public function ignoresParentAndForeignStaticPropertyFetches(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/LackOfCohesionRule/ExternalStaticProperties.php'],
            [
                ['Class ExternalStaticProperties splits into 2 disjoint method groups (LCOM4). Maximum allowed is 1.', 7],
            ],
            'parent::$x and OtherClass::$x must not connect methods — only self/static properties count',
        );
    }
}
