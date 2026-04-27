<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NestedTryDepthRule;

use Haspadar\PHPStanRules\Rules\NestedTryDepthRule;
use InvalidArgumentException;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<NestedTryDepthRule> */
final class NestedTryDepthRuleHigherLimitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new NestedTryDepthRule(2);
    }

    #[Test]
    public function passesTwoLevelsNestedWhenLimitIsTwo(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedTryDepthRule/TwoLevelsNested.php'],
            [],
        );
    }

    #[Test]
    public function acceptsZeroAsValidLimit(): void
    {
        $this->expectNotToPerformAssertions();

        new NestedTryDepthRule(0);
    }

    #[Test]
    public function rejectsNegativeMaxDepth(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('maxDepth must be a non-negative integer, -1 given');

        new NestedTryDepthRule(-1);
    }

    #[Test]
    public function reportsThreeLevelsNestedWhenLimitIsTwo(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedTryDepthRule/ThreeLevelsNested.php'],
            [
                [
                    'Nested try depth is 3 in method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedTryDepthRule\ThreeLevelsNested::run(). Maximum allowed is 2.',
                    14,
                ],
            ],
        );
    }
}
