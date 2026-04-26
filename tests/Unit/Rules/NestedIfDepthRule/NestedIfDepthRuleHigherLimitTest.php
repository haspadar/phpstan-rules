<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NestedIfDepthRule;

use Haspadar\PHPStanRules\Rules\NestedIfDepthRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<NestedIfDepthRule> */
final class NestedIfDepthRuleHigherLimitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new NestedIfDepthRule(2);
    }

    #[Test]
    public function passesTwoLevelsNestedWhenLimitIsTwo(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedIfDepthRule/TwoLevelsNested.php'],
            [],
        );
    }

    #[Test]
    public function acceptsZeroAsValidLimit(): void
    {
        new NestedIfDepthRule(0);

        self::assertTrue(true, 'Constructing the rule with maxDepth=0 must not throw');
    }

    #[Test]
    public function reportsThreeLevelsNestedWhenLimitIsTwo(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedIfDepthRule/ThreeLevelsNested.php'],
            [
                [
                    'Nested if depth is 3 in method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedIfDepthRule\ThreeLevelsNested::run(). Maximum allowed is 2.',
                    14,
                ],
            ],
        );
    }
}
