<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NestedIfDepthRule;

use Haspadar\PHPStanRules\Rules\NestedIfDepthRule;
use InvalidArgumentException;
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
        $this->expectNotToPerformAssertions();

        new NestedIfDepthRule(0);
    }

    #[Test]
    public function rejectsNegativeMaxDepth(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('maxDepth must be a non-negative integer, -1 given');

        new NestedIfDepthRule(-1);
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
