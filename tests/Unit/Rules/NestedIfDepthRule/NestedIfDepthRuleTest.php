<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NestedIfDepthRule;

use Haspadar\PHPStanRules\Rules\NestedIfDepthRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<NestedIfDepthRule> */
final class NestedIfDepthRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new NestedIfDepthRule(1);
    }

    #[Test]
    public function passesWhenMethodHasNoNestedIf(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedIfDepthRule/ShallowIf.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenNestingExactlyMatchesLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedIfDepthRule/OneLevelNested.php'],
            [],
        );
    }

    #[Test]
    public function reportsNestingThatExceedsLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedIfDepthRule/TwoLevelsNested.php'],
            [
                [
                    'Nested if depth is 2 in method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedIfDepthRule\TwoLevelsNested::run(). Maximum allowed is 1.',
                    13,
                ],
            ],
        );
    }

    #[Test]
    public function reportsEachIfThatBreaksTheLimitInDeepCascade(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedIfDepthRule/ThreeLevelsNested.php'],
            [
                [
                    'Nested if depth is 2 in method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedIfDepthRule\ThreeLevelsNested::run(). Maximum allowed is 1.',
                    13,
                ],
                [
                    'Nested if depth is 3 in method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedIfDepthRule\ThreeLevelsNested::run(). Maximum allowed is 1.',
                    14,
                ],
            ],
        );
    }

    #[Test]
    public function passesElseIfChainBecauseElseIfIsNotNesting(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedIfDepthRule/ElseIfChain.php'],
            [],
        );
    }

    #[Test]
    public function passesIfInsideClosureBecauseClosureResetsDepth(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedIfDepthRule/IfInsideClosure.php'],
            [],
        );
    }

    #[Test]
    public function passesIfInsideMatchBecauseMatchIsNotIfStatement(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedIfDepthRule/IfInsideMatch.php'],
            [],
        );
    }

    #[Test]
    public function passesAbstractMethodWithoutBody(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedIfDepthRule/AbstractMethod.php'],
            [],
        );
    }

    #[Test]
    public function passesSiblingNestedIfsBecauseDepthCounterResetsBetweenScopes(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedIfDepthRule/SiblingIfsWithNested.php'],
            [],
        );
    }

    #[Test]
    public function reportsNestedIfsAfterArrowFunctionRestoresScope(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedIfDepthRule/IfAfterArrowFunction.php'],
            [
                [
                    'Nested if depth is 2 in method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedIfDepthRule\IfAfterArrowFunction::run(). Maximum allowed is 1.',
                    15,
                ],
            ],
        );
    }

    #[Test]
    public function suppressesViolationWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedIfDepthRule/SuppressedNested.php'],
            [],
        );
    }
}
