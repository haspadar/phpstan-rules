<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\LackOfCohesionRule;

use Haspadar\PHPStanRules\Rules\LackOfCohesionRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<LackOfCohesionRule> */
final class LackOfCohesionRuleMinMethodsTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new LackOfCohesionRule(1, ['minMethods' => 5, 'minProperties' => 2]);
    }

    #[Test]
    public function skipsClassWithFewerMethodsThanMinMethods(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/LackOfCohesionRule/SmallClass.php'],
            [],
        );
    }
}
