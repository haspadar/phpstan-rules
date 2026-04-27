<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NestedTryDepthRule;

use Haspadar\PHPStanRules\Rules\NestedTryDepthRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<NestedTryDepthRule> */
final class NestedTryDepthRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new NestedTryDepthRule(1);
    }

    #[Test]
    public function passesWhenMethodHasNoNestedTry(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedTryDepthRule/ShallowTry.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenNestingExactlyMatchesLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedTryDepthRule/OneLevelNested.php'],
            [],
        );
    }

    #[Test]
    public function reportsNestingThatExceedsLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedTryDepthRule/TwoLevelsNested.php'],
            [
                [
                    'Nested try depth is 2 in method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedTryDepthRule\TwoLevelsNested::run(). Maximum allowed is 1.',
                    13,
                ],
            ],
        );
    }

    #[Test]
    public function reportsEachTryThatBreaksLimitInDeepCascade(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedTryDepthRule/ThreeLevelsNested.php'],
            [
                [
                    'Nested try depth is 2 in method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedTryDepthRule\ThreeLevelsNested::run(). Maximum allowed is 1.',
                    13,
                ],
                [
                    'Nested try depth is 3 in method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedTryDepthRule\ThreeLevelsNested::run(). Maximum allowed is 1.',
                    14,
                ],
            ],
        );
    }

    #[Test]
    public function passesTryInsideCatchWhenWithinLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedTryDepthRule/TryInsideCatch.php'],
            [],
        );
    }

    #[Test]
    public function passesTryInsideFinallyWhenWithinLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedTryDepthRule/TryInsideFinally.php'],
            [],
        );
    }

    #[Test]
    public function passesTryInsideClosureBecauseClosureResetsDepth(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedTryDepthRule/TryInsideClosure.php'],
            [],
        );
    }

    #[Test]
    public function passesAbstractMethodWithoutBody(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedTryDepthRule/AbstractMethod.php'],
            [],
        );
    }

    #[Test]
    public function passesSiblingNestedTriesBecauseDepthCounterResetsBetweenScopes(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedTryDepthRule/SiblingTriesWithNested.php'],
            [],
        );
    }

    #[Test]
    public function suppressesViolationWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedTryDepthRule/SuppressedNested.php'],
            [],
        );
    }
}
