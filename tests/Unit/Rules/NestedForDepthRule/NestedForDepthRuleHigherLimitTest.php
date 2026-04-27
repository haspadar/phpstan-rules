<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NestedForDepthRule;

use Haspadar\PHPStanRules\Rules\NestedForDepthRule;
use InvalidArgumentException;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<NestedForDepthRule> */
final class NestedForDepthRuleHigherLimitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new NestedForDepthRule(2);
    }

    #[Test]
    public function passesTwoLevelsNestedWhenLimitIsTwo(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedForDepthRule/TwoLevelsNested.php'],
            [],
        );
    }

    #[Test]
    public function acceptsZeroAsValidLimit(): void
    {
        new NestedForDepthRule(0);

        self::assertTrue(true, 'Constructing the rule with maxDepth=0 must not throw');
    }

    #[Test]
    public function rejectsNegativeMaxDepth(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('maxDepth must be a non-negative integer, -1 given');

        new NestedForDepthRule(-1);
    }

    #[Test]
    public function reportsThreeLevelsNestedWhenLimitIsTwo(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedForDepthRule/ThreeLevelsNested.php'],
            [
                [
                    'Nested loop depth is 3 in method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedForDepthRule\ThreeLevelsNested::dive(). Maximum allowed is 2.',
                    15,
                ],
            ],
        );
    }
}
